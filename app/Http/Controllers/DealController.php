<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    public function create($id)
    {
        // Получаем пост и продавца
        $deal_post = Post::with('user')->findOrFail($id);

        // Делаем переменную глобальной
        view()->share('deal', true);

        return view('deals.create', compact('deal_post'));
    }

    // Список сделок для админки
    public function adminDealList()
    {
        // Получаем все сделки с нужными отношениями
        $deals = Deal::with(['post', 'seller', 'buyer'])->orderByDesc('created_at')->get();

        // Статистика
        $completedDealsCount = Deal::where('status', 'завершена')->count();
        $cancelledDealsCount = Deal::where('status', 'отменена')->count();
        $totalAmount = Deal::sum('deal_amount'); // Сумма всех сделок
        $totalCommissions = Deal::where('status', 'завершена')
            ->sum(DB::raw('deal_amount * commission / 100')); // Заработанная комиссия в рублях только для завершённых сделок


        // Передаем данные в представление
        return view('admin.deal_list', compact('deals', 'completedDealsCount', 'cancelledDealsCount', 'totalAmount', 'totalCommissions'));
    }

    public function adminUpdateStatus(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);

        // Обновляем статус сделки
        $deal->status = $request->input('status');
        $deal->save();

        return redirect()->route('admin.deal-list')->with('success', 'Статус сделки успешно обновлен.');
    }

    // Метод для отображения списка сделок на отмену
    public function cancellationRequests()
    {
        // Получаем все сделки, которые ожидают отмены
        $deals = Deal::where('status', 'запрос отмены')->get();

        return view('admin.cancellation-requests', compact('deals'));
    }

    // Метод для принятия отмены сделки
    public function acceptCancellation($id)
    {
        $deal = Deal::findOrFail($id);

        // Проверка баланса перед списанием средств
        $buyer = User::find($deal->buyer_id);
        
        $totalAmount = $deal->deal_amount;  // сумму сделки
        $buyer->balance += $totalAmount;  // Покупателю возвращаем полную сумму сделки

        $buyer->save();

        // Обновляем статус сделки на "отменена"
        $deal->status = 'отменена';
        $deal->save();

        return redirect()->route('admin.deal.cancellation-requests')->with('success', 'Сделка отменена.');
    }

    // Метод для отказа от отмены сделки
    public function rejectCancellation($id)
    {
        $deal = Deal::findOrFail($id);

        // Обновляем статус сделки обратно на "выполняется" или другое значение
        $deal->status = 'выполняется';  // Например, восстанавливаем в статус выполнения
        $deal->save();

        return redirect()->route('admin.deal.cancellation-requests')->with('success', 'Отказано в отмене сделки.');
    }

    public function store(Request $request)
    {
        $buyer = auth()->user(); // Получаем текущего покупателя

        // Валидация входных данных
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'deal_amount' => 'required|numeric',
            'desired_datetime' => 'required',
            'vin_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->route('deal.create', ['id' => $request->input('post_id')])
                ->withErrors($validator) // Передача ошибок в сессию
                ->withInput(); // Передача старых данных в сессию
        }

        $validated = $validator->validated();

        // Получение поста и его категории
        $post = Post::with('category')->findOrFail($validated['post_id']);
        $category = $post->category;

        // Получаем комиссию из настроек
        $defaultCommission = DB::table('commission_settings')->where('name', 'Default Commission')->value('value');
        $commissionPercentage = (is_null($category->commission) || floatval($category->commission) == 0.0) ? $defaultCommission : $category->commission;

        // Получаем таймер из настроек
        $defaultTimer = DB::table('timer_settings')->where('name', 'Default Timer')->value('value');
        $timer = $defaultTimer; // Используем таймер категории или настройку по умолчанию

        // Проверка баланса покупателя
        $totalAmount = $validated['deal_amount']; // Общая сумма сделки
        if ($buyer->balance < $totalAmount) {
            return redirect()->route('deal.create', ['id' => $validated['post_id']])
                ->with('error', 'Недостаточно средств для создания сделки. Пополните баланс.');
        }

        // Создание новой сделки
        $deal = new Deal();
        $deal->post_id = $validated['post_id'];
        $deal->seller_id = $post->user_id; // Продавец
        $deal->buyer_id = $buyer->id; // Покупатель
        $deal->deal_amount = $validated['deal_amount'];
        $deal->commission = $commissionPercentage;
        $deal->timer = $timer;
        $deal->desired_time = $validated['desired_datetime'];
        $deal->vin_number = $validated['vin_number'];
        $deal->save();

        return redirect()->route('deals.my')->with('success', 'Сделка успешно создана.');
    }



    public function accept($id)
    {
        $deal = Deal::findOrFail($id);

        // Проверка баланса перед списанием средств
        $buyer = User::find($deal->buyer_id);
        
        $totalAmount = $deal->deal_amount;  // Покупатель платит полную сумму сделки
        $buyer->balance -= $totalAmount;  // Покупатель платит полную сумму сделки

        $buyer->save();
        
        // Обновление статуса сделки
        $deal->status = 'выполняется';
        $deal->save();

        return redirect()->back()->with('success', 'Сделка принята.');
    }

    public function complete($id)
    {
        $deal = Deal::findOrFail($id);

        // Списание средств у покупателя и начисление продавцу
        $seller = User::find($deal->seller_id);

        $commissionAmount = ($deal->deal_amount * $deal->commission) / 100;  // Вычисляем комиссию как процент от суммы сделки
        
        $seller->balance += ($deal->deal_amount - $commissionAmount);  // Продавцу начисляется сумма сделки минус комиссия
        $seller->save();

        // Завершение сделки
        $deal->status = 'завершена';
        $deal->save();

        return redirect()->back()->with('success', 'Сделка успешно завершена.');
    }

    public function myDeals()
    {
        // Получаем сделки текущего пользователя
        $deals = Deal::where('buyer_id', auth()->id())
            ->orWhere('seller_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('deals.my_deals', compact('deals'));
    }

    public function view($id)
    {
        $deal = Deal::findOrFail($id);
        return view('deals.view', compact('deal'));
    }

    public function reject($id)
    {
        $deal = Deal::findOrFail($id);

        $deal->status = 'отклонена';
        $deal->save();

        return redirect()->back()->with('success', 'Сделка успешно отклонена.');
    }

    public function requestCancel(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);

        $deal->status = 'запрос отмены';
        $deal->cancellation_reason = $request->cancel_reason;
        $deal->save();

        return redirect()->back()->with('success', 'Запрос на отмену сделки отправлен.');
    }
}
