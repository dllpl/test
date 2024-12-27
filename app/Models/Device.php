<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'serial_number', 'contract_number'];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
