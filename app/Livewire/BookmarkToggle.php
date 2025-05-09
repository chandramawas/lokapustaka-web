<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookmarkToggle extends Component
{
    public Book $book;
    public bool $isBookmarked;

    public function mount(Book $book)
    {
        $this->book = $book;
        $this->isBookmarked = auth()->user()->savedBooks->contains($book->id);
    }

    public function toggle()
    {
        $user = auth()->user();

        if ($this->isBookmarked) {
            $user->savedBooks()->detach($this->book->id);
        } else {
            $user->savedBooks()->attach($this->book->id);
        }

        $this->isBookmarked = !$this->isBookmarked;
    }

    public function render()
    {
        return view('livewire.bookmark-toggle');
    }
}
