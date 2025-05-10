@props(['book' => null])

<x-buttons.button :href="route('book.reviews', $book->slug)" variant="custom" icon
    class="shadow-sm hover:shadow-md border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark bg-secondary-container dark:bg-secondary-container-dark text-on-secondary-container dark:text-on-secondary-container-dark hover:bg-secondary-container/80 dark:hover:bg-secondary-container-dark/80 hover:text-on-secondary-container/80 dark:hover:text-on-secondary-container-dark/80">
    <x-icons.star />
    <span>
        {{ $book->rating_summary['average'] . ' (' . $book->rating_summary['count'] . ' Ulasan)' ?? 'Belum ada ulasan' }}
    </span>
</x-buttons.button>