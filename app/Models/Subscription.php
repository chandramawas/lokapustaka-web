<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function ($subscription) {
            static::where('user_id', $subscription->user_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $subscription->is_active = true;
        });
    }

    // Relasi ke model user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
