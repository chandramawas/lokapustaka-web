<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'cfi',
        'progress_percent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeBooksBeingRead($query, $user)
    {
        // Ambil data ReadingProgress user, lalu eager load relasi 'book' dan filter progress > 0
        return $query->where('user_id', $user->id)
            ->where('progress_percent', '>', 0)  // Progress yang lebih dari 0
            ->with('book')  // Eager load 'book' relasi
            ->get()
            ->pluck('book');  // Ambil hanya relasi 'book' nya aja
    }

    public function scopeBooksReadNotCompleted($query, $user)
    {
        return $query->where('user_id', $user->id)
            ->where('progress_percent', '<', 99)  // Progress yang kurang dari 0
            ->with('book')  // Eager load 'book' relasi
            ->get()
            ->pluck('book');  // Ambil hanya relasi 'book' nya aja
    }
}
