<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'slug',
        'epub_path',
        'title',
        'author',
        'publisher',
        'year',
        'pages',
        'language',
        'description',
        'cover_url',
    ];

    // Slugify
    protected static function booted()
    {
        static::created(function ($book) {
            $book->slug = Str::slug($book->title . '-' . $book->id);
            $book->save();
        });

        static::updating(function ($book) {
            if ($book->isDirty('title')) {
                $book->slug = Str::slug($book->title . '-' . $book->id);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function getMainGenreAttribute()
    {
        return $this->genres()->first();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratingSummary(): Attribute
    {
        return Attribute::get(function () {
            return [
                'average' => round($this->reviews()->avg('rating'), 1),
                'count' => $this->reviews()->count(),
            ];
        });
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }

    public function scopeSort($query, $sort)
    {
        switch ($sort) {
            case 'az':
                return $query->orderBy('title', 'asc');
            case 'popular':
                return $query->withCount('readingProgress')->orderBy('reading_progress_count', 'desc');
            case 'rating':
                return $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
            case 'newest':
                return $query->latest();
            default:
                return $query->orderBy('title', 'asc');
        }
    }

    public function scopeFilterByGenre($query, $genreId)
    {
        return $query->whereHas('genres', function ($q) use ($genreId) {
            $q->where('genres.id', $genreId);
        });
    }

    public function getReadingProgress(User $user)
    {
        return $user->readingProgress()->where('book_id', $this->id)->first();
    }
}
