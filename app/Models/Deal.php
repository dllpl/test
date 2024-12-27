<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'seller_id', 'buyer_id', 'deal_amount', 'commission', 
        'timer', 'desired_datetime', 'vin_number'
    ];

    // Связь с постом
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Связь с продавцом
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Связь с покупателем
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
