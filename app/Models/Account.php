<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * Таблица, связанная с моделью.
     */
    protected $table = 'accounts';

    /**
     * Поля, разрешенные для массового заполнения.
     */
    protected $fillable = [
        'user_id',
        'recipient',
        'inn',
        'bank_name',
        'bik',
        'correspondent_account',
        'account_number',
    ];

    /**
     * Отношение: Счет принадлежит пользователю.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
