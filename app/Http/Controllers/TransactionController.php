<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WithdrawRequest;
use App\Models\Transaction; // Модель для операций

class TransactionController extends Controller
{
    public function withdrawRequests()
    {
        // Получить запросы текущего пользователя
        $withdrawRequests = WithdrawRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.withdraw_requests', compact('withdrawRequests'));
    }

    public function transactionHistory()
    {
        // Получить историю операций текущего пользователя
        $transactions = Transaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.history', compact('transactions'));
    }
}
