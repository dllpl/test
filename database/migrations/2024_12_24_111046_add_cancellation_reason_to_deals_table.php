<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('deals', function (Blueprint $table) {
        $table->text('cancellation_reason')->nullable();  // Поле для причины отмены
    });
}

public function down()
{
    Schema::table('deals', function (Blueprint $table) {
        $table->dropColumn('cancellation_reason');
    });
}
};
