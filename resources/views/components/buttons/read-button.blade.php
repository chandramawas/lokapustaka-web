@props(['book' => null])

@if (auth()->user()->isSubscribed)
    <x-buttons.button :href="route('book.read', $book->slug ?? '#')" variant="primary" icon>
        <x-icons.book-read />
        @if ($book->progress)
            @if ($book->progress->progress_percent > 98)
                <span>Baca Lagi</span>
            @else
                <span>Lanjut Baca</span>
            @endif
        @else
            <span>Baca Sekarang</span>
        @endif
    </x-buttons.button>
@else
    <x-buttons.button :href="route('subscription.index')" variant="primary" icon>
        <x-icons.subscribe />
        <span>Berlangganan Sekarang</span>
    </x-buttons.button>
@endif