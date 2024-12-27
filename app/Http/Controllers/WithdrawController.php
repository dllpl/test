<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class WithdrawController extends Controller
{

    public function showWithdrawForm()
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        view()->share('accounts', $accounts); // Делает переменную доступной во всех представлениях
    }

    public function submitWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_id' => 'required|exists:accounts,id', // Добавлено для проверки выбранного счета
        ]);

        $user = auth()->user();

        // Проверка баланса пользователя перед созданием запроса
        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'Недостаточно средств для вывода. Проверьте баланс.');
        }

        // Создать запрос на вывод и обновить баланс
        \DB::transaction(function () use ($request, $user) {
            $user->withdrawRequests()->create([
                'amount' => $request->amount,
                'account_id' => $request->account_id, // Сохраняем выбранный счет
            ]);

            // Уменьшить баланс пользователя
            $user->decrement('balance', $request->amount);
        });

        return redirect()->back()->with('success', 'Ваш запрос на вывод успешно отправлен.');
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);  // Находим счет по ID
        return view('admin.accounts.edit', compact('account'));
    }


    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        
        $validated = $request->validate([
            'recipient'         => 'required|string',
            'inn'               => 'required|string',
            'bank_name'         => 'required|string',
            'bik'               => 'required|string',
            'account_number'    => 'required|string',
        ]);
        
        $account->update($validated);

        return redirect()->route('accounts.edit', ['id' => $account->id])->with('success', trans('admin.Account updated successfully'));
    }

    
}
