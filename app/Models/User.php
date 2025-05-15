<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPasswordEmail;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'birthdate',
        'email_verified_at',
    ];

    protected $appends = ['is_subscribed'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordEmail($token, $this->email));
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate
            ? Carbon::parse($this->birthdate)->age
            : null;
    }

    // Relasi ke model Subcription
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    public function getIsSubscribedAttribute(): bool
    {
        return (bool)$this->activeSubscription();
    }

    // Relasi ke model Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function savedBooks()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }
}
