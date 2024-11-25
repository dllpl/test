<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TinkoffController extends Controller
{
    /**
     * Отправка платежа в Тинькофф для пополнения баланса
     */
    public function sendPayment(Request $request)
    {
        // Получаем пользователя и сумму пополнения
        $user = User::find($request->input('user_id'));
        $amount = $request->input('amount');

        if (!$user || !$amount || $amount <= 0) {
            return redirect()->back()->with('error', 'Неверные данные пользователя или суммы.');
        }

        // Данные для запроса в Тинькофф
        $data = [
            "TerminalKey" => '1712219953971',
            "Amount" => $amount * 100, // Сумма в копейках
            "OrderId" => uniqid(), // Генерация уникального идентификатора заказа
            "Description" => "Пополнение баланса пользователя с ID {$user->id}",
            "SuccessURL" => route('tinkoff.success'),
            "FailURL" => route('tinkoff.fail'),
            "NotificationURL" => route('tinkoff.callback'),
            "DATA" => [
                "user_id" => $user->id,
            ],
        ];

        // Выполняем запрос на инициализацию платежа в Тинькофф
        $ch = curl_init('https://securepay.tinkoff.ru/v2/Init');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);

        if (!empty($response['PaymentURL'])) {
            session()->forget(['transaction_id', 'user_id', 'amount']);
            // Сохраняем параметры транзакции в сессии
            session()->put('transaction_id', $response['PaymentId']);
            session()->put('user_id', $user->id);
            session()->put('amount', $amount);

            // Перенаправляем пользователя на страницу оплаты
            return redirect($response['PaymentURL']);
        } else {
            return redirect()->back()->with('error', 'Ошибка при инициализации платежа.');
        }
    }

    /**
     * Обработка callback от Тинькофф Банка
     */
    public function handleCallback(Request $request)
    {
        // Получаем данные из сессии
        $transactionId = session('transaction_id');
        $userId = session('user_id');
        $amount = session('amount');

        if (!$transactionId || !$userId || !$amount) {
            return response()->json(['error' => 'Ошибка при получении данных транзакции'], 400);
        }

        // Проверяем статус платежа (этот процесс можно усложнить с проверкой подписи и статусов Тинькофф)
        if ($request->input('Success') == true) {
            // Успешный платеж, пополняем баланс пользователя
            $user = User::find($userId);
            if ($user) {
                $user->balance += $amount;
                $user->save();
            }

            return redirect('/')->with('success', 'Баланс успешно пополнен.');
        } else {
            return redirect('/')->with('error', 'Платеж не был успешен.');
        }
    }
    
    public function paymentSuccess(Request $request)
    {
        $userId = session('user_id');
        $amount = session('amount');
    
        // Важно проверить, что сессия содержит нужные данные
        if (!$userId || !$amount) {
            return redirect('/')->with('error', 'Неверные данные для зачисления.');
        }
    
        // Получаем пользователя
        $user = User::find($userId);
        if ($user) {
            // Увеличиваем баланс
            $user->balance += $amount;
            $user->save();
    
            // Очищаем данные сессии после успешного зачисления
            session()->forget(['user_id', 'amount']);
    
            return redirect('/')->with('success', 'Баланс успешно пополнен.');
        }
    
        return redirect('/')->with('error', 'Пользователь не найден.');
    }


    
    /**
     * Перенаправление на страницу ошибки после неудачной оплаты.
     */
    public function paymentFail()
    {
        return redirect('/')->with('error', 'Платеж не был успешен.');
    }
}
