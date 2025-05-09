@props(['book' => null, 'progress' => null])

@if (auth()->user()->isSubscribed)
    <x-buttons.button :href="route('book.read', $book->slug)" variant="primary" icon>
        <x-icons.book-read />
        @if ($progress)
            @if ($progress->progress_percent > 99)
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