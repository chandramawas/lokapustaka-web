<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount',
        'status',
        'method',
        'paid_at',
        'order_id',
    ];

    //Relasi ke model Subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    //Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
