<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static withCount(string $string)
 * @method static has(string $string, string $string1, int $int)
 */
class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Slugify
    protected static function booted()
    {
        static::creating(function ($genre) {
            $genre->slug = Str::slug($genre->name);
        });

        static::updating(function ($genre) {
            $genre->slug = Str::slug($genre->name);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
