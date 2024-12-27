<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = 1; // ID пользователя

        Transaction::insert([
            [
                'user_id' => $userId,
                'description' => 'Пополнение баланса',
                'payment_method' => 'Tinkoff',
                'amount' => 1500.00,
                'status' => 'completed',
                'created_at' => now(),
            ],
            [
                'user_id' => $userId,
                'description' => 'Оплата за сделку',
                'payment_method' => 'Внутренний баланс',
                'amount' => -500.00,
                'status' => 'completed',
                'created_at' => now(),
            ],
        ]);
    }
}

