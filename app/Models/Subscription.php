<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_status',
        'payment_expiry',
        'order_id',
    ];

    // Связь: подписка принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
