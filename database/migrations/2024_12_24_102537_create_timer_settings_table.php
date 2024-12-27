<?php
// database/migrations/xxxx_xx_xx_create_timer_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('timer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название настройки (например, "Default Timer")
            $table->integer('value')->default(0); // Время в минутах
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('timer_settings');
    }
};

