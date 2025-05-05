<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $recommendationBook = Book::query()->first();

        return view('home', compact('recommendationBook'));
    }
}
