<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class BalanceController extends Controller
{
    // Метод для получения текущего баланса по ID
    public function getBalance($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'balance' => $user->balance
            ], 200);
        }

        return response()->json([
            'error' => 'User not found'
        ], 404);
    }

    public function updateBalance(Request $request, $id)
    {
        $user = User::find($id);
    
        // Проверка, что запрос содержит сумму для изменения баланса
        $request->validate([
            'amount' => 'required|numeric'
        ]);
    
        if ($user) {
            $amount = $request->input('amount');
    
            // Если сумма отрицательная и недостаточно средств
            if ($amount < 0 && $user->balance + $amount < 0) {
                return response()->json([
                    'error' => 'Insufficient funds'
                ], 400);
            }
    
            // Изменение баланса
            $user->balance += $amount;
            $user->save();
    
            return response()->json([
                'message' => 'Balance updated successfully',
                'balance' => $user->balance
            ], 200);
        }
    
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }

}
