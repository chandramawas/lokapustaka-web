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

    public static function recommendationBook()
    {
        return self::inRandomOrder()->first();
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
