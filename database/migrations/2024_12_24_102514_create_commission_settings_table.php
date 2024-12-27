<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название настройки (например, "Default Commission")
            $table->decimal('value', 5, 2)->default(0); // Значение комиссии
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_settings');
    }
};