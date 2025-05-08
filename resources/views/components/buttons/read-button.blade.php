@props(['book' => null])

<x-buttons.button :href="route('book.read', $book->slug)" variant="primary" icon>
    <x-icons.book-read />
    <span>Baca Sekarang</span>
</x-buttons.button>