<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredBooks = Book::sort('popular')->limit(6)->get();

        return view("welcome", compact('featuredBooks'));
    }
}
