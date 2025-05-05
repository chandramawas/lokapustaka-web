<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'year',
        'isbn',
        'pages',
        'language',
        'description',
        'cover_url',
    ];

    public function getRouteKeyName()
    {
        return 'isbn';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    protected function ratingSummary(): Attribute
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
}
