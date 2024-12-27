<?php

// 2024_12_16_000000_create_deals_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    public function up()
    {
        schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('buyer_id');
            $table->decimal('deal_amount', 10, 2);
            $table->decimal('commission', 5, 2);
            $table->integer('timer'); // Время в минутах
            $table->timestamp('desired_time');
            $table->string('vin_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deals');
    }
}

