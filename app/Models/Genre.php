<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
