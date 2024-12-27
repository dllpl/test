<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class BillController extends Controller
{
    public function list()
    {
        // Получить список счетов текущего пользователя
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('bill.list', compact('accounts'));
    }

    public function create()
    {
        // Показать форму добавления счета
        return view('bill.create');
    }

    public function store(Request $request)
    {
        // Валидация данных формы
        $request->validate([
            'recipient' => 'required|string|max:255',
            'inn' => 'required|string|max:12',
            'bank_name' => 'required|string|max:255',
            'bik' => 'required|string|max:9',
            'correspondent_account' => 'required|string|max:20',
            'account_number' => 'required|string|max:20',
        ]);

        // Сохранение нового счета
        Account::create([
            'user_id' => auth()->id(),
            'recipient' => $request->recipient,
            'inn' => $request->inn,
            'bank_name' => $request->bank_name,
            'bik' => $request->bik,
            'correspondent_account' => $request->correspondent_account,
            'account_number' => $request->account_number,
        ]);

        return redirect()->route('bill.list')->with('success', 'Счет успешно добавлен.');
    }
}
