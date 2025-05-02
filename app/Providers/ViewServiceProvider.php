<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Genre;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // View Composer untuk navbar
        View::composer('layouts.navbar', function ($view) {
            // Query kategori dan genre untuk ditampilkan di navbar
            $categories = Category::orderBy('name')->get();
            $genres = Genre::orderBy('name')->get();

            // Share data ke view
            $view->with('categories', $categories);
            $view->with('genres', $genres);
        });
    }

    public function register(): void
    {
        //
    }
}
