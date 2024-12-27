<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // ID пользователя
            $table->string('description'); // Описание
            $table->string('payment_method'); // Способ оплаты
            $table->decimal('amount', 10, 2); // Сумма
            $table->timestamp('created_at')->useCurrent(); // Дата транзакции
            $table->string('status')->default('completed'); // Статус (completed, pending, failed)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

