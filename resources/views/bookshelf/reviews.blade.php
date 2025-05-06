@extends('layouts.app-bookshelf')

@section('title', 'Ulasan Saya')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Koleksi Saya', 'url' => route('bookshelf.index')],
            ['label' => 'Ulasan Saya', 'url' => route('bookshelf.reviews')],
        ]" />
@endsection

@section('bookshelf-title', 'Ulasan Saya')

@section('bookshelf-content')
    {{-- Filter --}}
    <div class="space-y-1">
        <h3 class="font-medium text-body-md">Filter Ulasan</h3>

        {{-- Filter --}}
        <form method="GET" class="flex gap-2 items-center text-label font-normal justify-between">
            <div class="flex flex-wrap gap-2 items-center">
                {{-- Filter Rating --}}
                <x-forms.select name="rating" :value="request('rating')" size="sm" :options="[
            '' => 'Semua Rating',
            '1' => '1 Bintang',
            '2' => '2 Bintang',
            '3' => '3 Bintang',
            '4' => '4 Bintang',
            '5' => '5 Bintang',
        ]" />

                {{-- Filter Sort --}}
                <x-forms.select name="sort" :value="request('sort')" size="sm" :options="[
            'latest' => 'Terbaru',
            'oldest' => 'Terlama',
            'highest' => 'Tertinggi',
            'lowest' => 'Terendah',
        ]" />

                {{-- Filter Review Teks --}}
                <x-forms.select name="text" :value="request('text')" size="sm" :options="[
            '' => 'Semua Ulasan',
            'yes' => 'Dengan Teks',
            'no' => 'Tanpa Teks',
        ]" />
            </div>

            <x-buttons.button type="submit" variant="secondary">
                Terapkan Filter
            </x-buttons.button>
        </form>
    </div>

    {{-- Review List --}}
    <div x-data="{ editingId: null }" class="space-y-2 overflow-y-auto dropdown-scroll">
        @forelse ($reviews as $review)
            <div class="flex bg-surface-container-low dark:bg-surface-container-low-dark p-3 rounded-xl shadow-md gap-2 w-full">
                {{-- Cover --}}
                <img src="{{ $review->book->cover_url ?? 'https://placehold.co/150x220?text=Cover+not+available.' }}"
                    loading="lazy" class="h-[100px] md:h-[250px] rounded-md object-cover aspect-[2/3]"
                    alt="Cover {{ $review->book->author ?? 'Author' }}'s {{ $review->book->title ?? 'Title' }}">

                <div data-review-id="{{ $review->id }}" x-data="{ reviewId: {{ $review->id }} }" x-show="true"
                    class="w-full space-y-2 flex flex-col">
                    {{-- Header: Info & Button --}}
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-bold text-body-sm md:text-body-xl">
                                {{ $review->book->title }}
                            </p>
                            <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                                {{ $review->created_at->diffForHumans() }}
                                @if ($review->updated_at->gt($review->created_at))
                                    <span class="italic">
                                        (diedit {{ $review->updated_at->diffForHumans() }})
                                    </span>
                                @endif
                            </p>
                        </div>

                        {{-- Rating Static (kalau nggak di-edit) --}}
                        <div class="flex gap-0.5" x-show="editingId !== reviewId">
                            @for ($j = 1; $j <= 5; $j++)
                                <x-icons.star variant="filled" height="24px"
                                    class="{{ $j <= $review->rating ? 'text-secondary-container dark:text-secondary-container-dark' : 'text-outline-variant dark:text-outline-variant-dark' }}" />
                            @endfor
                        </div>
                    </div>

                    {{-- Review Text (non-edit mode) --}}
                    <div x-show="editingId !== reviewId" class="w-full flex flex-col gap-2">
                        <p class="text-label md:text-body-sm text-justify">
                            {{ $review->review ?? '(Tidak ada Ulasan)' }}
                        </p>

                        <div class="flex gap-2">
                            <x-buttons.button variant="secondary" @click="editingId = reviewId">
                                Edit Ulasan
                            </x-buttons.button>
                            <x-buttons.button :href="route('book.detail', $review->book->isbn)" variant="outline">
                                Lihat Buku
                            </x-buttons.button>
                        </div>
                    </div>

                    {{-- Edit Form --}}
                    <form method="POST" action="{{ route('book.review.update', [$review->book->isbn, $review->id]) }}"
                        x-show="editingId === reviewId" class="space-y-2" x-cloak>
                        @csrf
                        @method('PUT')

                        {{-- Rating --}}
                        <div class="space-y-1">
                            <h4 class="font-medium">Rating</h4>
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <x-icons.star height="32px" variant="filled"
                                        class="star-edit text-outline-variant dark:text-outline-variant-dark cursor-pointer transition-colors duration-150"
                                        :data-rating="$i" />
                                @endfor
                            </div>
                            @error('rating')
                                <x-forms.label textAlign="left">{{ $message }}</x-forms.label>
                            @enderror
                            <input type="hidden" name="rating" value="{{ $review->rating }}">
                        </div>

                        {{-- Textarea --}}
                        <div class="space-y-1">
                            <h4 class="font-medium">Ulasan</h4>
                            <x-forms.text-area name="review" rows="3" value="{{ $review->review }}" />
                        </div>

                        <div class="flex justify-end gap-2">
                            <x-buttons.button type="button" variant="secondary" @click="editingId = null">
                                Batal
                            </x-buttons.button>
                            <x-buttons.button type="submit" variant="primary">
                                Simpan
                            </x-buttons.button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                Tidak ada data.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="flex items-center text-label">
        {{-- Tombol Previous --}}
        <div class="w-1/3 flex justify-start">
            @if (!$reviews->onFirstPage())
                <a href="{{ $reviews->previousPageUrl() }}"
                    class="w-fit flex items-center justify-center rounded-md text-center space-x-0.5 transition px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark">
                    <x-icons.chevron-left />
                    <span class="hidden md:block">Sebelumnya</span>
                </a>
            @endif
        </div>

        {{-- Info Halaman di Tengah --}}
        <div class="w-1/3 flex justify-center">
            <div
                class="w-fit rounded-md text-center px-3 py-1 bg-surface-dim dark:bg-surface-dim-dark text-on-surface dark:text-on-surface-dark">
                <span class="hidden md:inline-block">Halaman</span>
                {{ $reviews->currentPage() }} / {{ $reviews->lastPage() }}
            </div>
        </div>

        {{-- Tombol Next --}}
        <div class="w-1/3 flex justify-end">
            @if ($reviews->hasMorePages())
                <a href="{{ $reviews->nextPageUrl() }}"
                    class="w-fit flex items-center justify-center rounded-md text-center space-x-0.5 transition px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark">
                    <span class="hidden md:block">Berikutnya</span>
                    <x-icons.chevron-right />
                </a>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-review-id]').forEach(reviewContainer => {
                const reviewId = reviewContainer.getAttribute('data-review-id');
                const stars = reviewContainer.querySelectorAll('.star-edit');
                const input = reviewContainer.querySelector('input[name="rating"]');

                let current = parseInt(input.value || 0);

                function updateStars(rating) {
                    stars.forEach((star, i) => {
                        if (i < rating) {
                            star.classList.remove('text-outline-variant', 'dark:text-outline-variant-dark');
                            star.classList.add('text-secondary-container', 'dark:text-secondary-container-dark');
                        } else {
                            star.classList.remove('text-secondary-container', 'dark:text-secondary-container-dark');
                            star.classList.add('text-outline-variant', 'dark:text-outline-variant-dark');
                        }
                    });
                }

                stars.forEach((star, i) => {
                    star.addEventListener('click', () => {
                        current = i + 1;
                        input.value = current;
                        updateStars(current);
                    });

                    star.addEventListener('mouseenter', () => {
                        updateStars(i + 1);
                    });

                    star.addEventListener('mouseleave', () => {
                        updateStars(current);
                    });
                });

                updateStars(current);
            });
        });
    </script>
@endpush