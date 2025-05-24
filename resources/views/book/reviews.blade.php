@extends('layouts.app-book')

@section('title', $book->title)

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => $book->mainGenre->name, 'url' => route('book.genre.collection', $book->mainGenre->slug)],
            ['label' => $book->title, 'url' => route('book.detail', $book->slug)],
            ['label' => 'Ulasan', 'url' => route('book.reviews', $book->slug)],
        ]"/>
@endsection

@section('first-content')
    {{-- Cover Buku --}}
    <img src="{{ $book->cover_url ?? 'https://placehold.co/150x220?text=Poster+not+available.' }}"
         class="border border-outline-variant dark:border-outline-variant-dark object-cover aspect-[2/3] w-full">

    {{-- Core Buku --}}
    <div class="space-y-0.5">
        <h3
            class="font-medium text-body-sm md:text-body-md lg:text-body-lg text-on-surface-variant dark:text-on-surface-variant-dark line-clamp-2">
            {{ $book->author }}
        </h3>
        <h2 class="font-bold text-heading-sm lg:text-heading-md line-clamp-2">
            {{ $book->title }}
        </h2>
        <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark line-clamp-2">
            {{ $book->genres->pluck('name')->join(', ') }}
        </p>
    </div>

    {{-- Overal Rating --}}
    <x-buttons.button variant="custom" icon
                      class="w-full shadow-sm hover:shadow-md bg-secondary-container dark:bg-secondary-container-dark text-on-secondary-container dark:text-on-secondary-container-dark pointer-events-none">
        <x-icons.star/>
        <span>
            {{ $book->rating_summary['average'] . ' (' . $book->rating_summary['count'] . ' Ulasan)' ?? 'Belum ada ulasan' }}
        </span>
    </x-buttons.button>
@endsection

@section('second-content')
    {{-- Section Judul --}}
    <div class="space-y-0.5 md:space-y-1">
        <h3 class="font-bold text-heading-sm md:text-heading-md lg:text-heading-lg">Ulasan Pembaca</h3>
        <p class="text-label md:text-body-sm text-on-surface-variant dark:text-on-surface-variant-dark">
            Lihat apa kata pembaca lain tentang buku ini, dan bagikan pendapatmu juga.
        </p>
    </div>
    @if (session('error'))
        <x-forms.label textAlign="center">{{ session('error') }}</x-forms.label>
    @elseif (session('success'))
        <x-forms.label variant="success" textAlign="center">{{ session('success') }}</x-forms.label>
    @endif

    {{-- CTA: Tulis/Edit Review --}}
    {{-- Jika Sudah Review => Edit Review --}}
    @if ($userReview)
        <div x-data="{ editMode: false }"
             class="bg-surface-container-low dark:bg-surface-container-low-dark p-3 rounded-xl shadow-md space-y-2 border border-primary">

            {{-- Header: Info & Button --}}
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-body-sm">Ulasan Kamu</p>
                    <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                        {{ $userReview->created_at->diffForHumans() }}
                        @if ($userReview->updated_at->gt($userReview->created_at))
                            <span class="italic">
                                (diedit {{ $userReview->updated_at->diffForHumans() }})
                            </span>
                        @endif
                    </p>
                </div>

                {{-- Rating Static (kalau nggak di-edit) --}}
                <div class="flex gap-0.5" x-show="!editMode">
                    @for ($j = 1; $j <= 5; $j++)
                        <x-icons.star variant="filled" height="24px"
                                      class="{{ $j <= $userReview->rating ? 'text-secondary-container dark:text-secondary-container-dark' : 'text-outline-variant dark:text-outline-variant-dark' }}"/>
                    @endfor
                </div>
            </div>

            {{-- Review Text (non-edit mode) --}}
            <div x-show="!editMode" class="space-y-2">
                @if ($userReview->review)
                    <p class="text-label md:text-body-sm text-justify">
                        {{ $userReview->review }}
                    </p>
                @endif

                <div class="text-end">
                    <x-buttons.button variant="primary" @click="editMode = true">
                        Edit Ulasan
                    </x-buttons.button>
                </div>
            </div>

            {{-- Edit Form --}}
            <form method="POST" action="{{ route('book.review.update', [$book->slug, $userReview->id]) }}"
                  x-show="editMode"
                  class="space-y-2" x-cloak>
                @csrf
                @method('PUT')

                {{-- Rating --}}
                <div class="space-y-1">
                    <h4 class="font-medium">Rating</h4>
                    <div class="flex items-center gap-1" id="edit-rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-icons.star height="32px" variant="filled"
                                          class="star-edit text-outline-variant dark:text-outline-variant-dark cursor-pointer transition-colors duration-150"
                                          :data-rating="$i"/>
                        @endfor
                    </div>
                    @error('rating')
                    <x-forms.label textAlign="left">{{ $message }}</x-forms.label>
                    @enderror
                    <input type="hidden" name="rating" id="edit-rating-input" value="{{ $userReview->rating }}">
                </div>

                {{-- Textarea --}}
                <div class="space-y-1">
                    <h4 class="font-medium">Ulasan</h4>
                    <x-forms.text-area name="review" rows="3" value="{{ $userReview->review }}"/>
                </div>

                <div class="flex justify-end gap-2">
                    <x-buttons.button type="button" variant="secondary" @click="editMode = false">
                        Batal
                    </x-buttons.button>
                    <x-buttons.button type="submit" variant="primary">
                        Simpan
                    </x-buttons.button>
                </div>
            </form>
        </div>

        {{-- JS Rating bintang edit --}}
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const stars = document.querySelectorAll('#edit-rating-stars .star-edit');
                    const input = document.getElementById('edit-rating-input');

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
            </script>
        @endpush
    @else
        {{-- Jika Belum Review => Buat Review --}}
        <div
            class="bg-surface dark:bg-surface-dark border border-outline-variant dark:border-outline-variant-dark rounded-xl p-3 space-y-2">
            <form method="POST" action="{{ route('book.review.store', $book->slug) }}"
                  class="flex flex-col gap-2 md:gap-4 text-body-md md:text-body-lg lg:text-body-xl">
                @csrf
                {{-- Rating (bintang) --}}
                <div class="space-y-1">
                    <h4 class="font-medium">Rating</h4>
                    <div class="flex items-center gap-1" id="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-icons.star height="32px" variant="filled"
                                          class="star text-outline-variant dark:text-outline-variant-dark cursor-pointer transition-colors duration-150"
                                          :data-rating="$i"/>
                        @endfor
                    </div>
                    @error('rating')
                    <x-forms.label textAlign="left">{{ $message }}</x-forms.label>
                    @enderror
                </div>
                <input type="hidden" name="rating" id="rating-input">

                {{-- Textarea Review --}}
                <div class="space-y-1">
                    <h4 class="font-medium">Ulasan</h4>
                    <x-forms.text-area name="review" placeholder="Bagikan pengalamanmu membaca buku ini..." rows="3"/>
                </div>

                {{-- Tombol Submit --}}
                <div class="flex justify-end">
                    <x-buttons.button type="submit" variant="primary">
                        Kirim Ulasan
                    </x-buttons.button>
                </div>
            </form>
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const stars = document.querySelectorAll('#rating-stars .star');
                        const ratingInput = document.getElementById('rating-input');

                        let selectedRating = parseInt(ratingInput.value || 0); // Simpan rating yang dipilih

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

                        // Klik = simpan rating
                        stars.forEach((star, index) => {
                            star.addEventListener('click', () => {
                                selectedRating = index + 1;
                                ratingInput.value = selectedRating;
                                updateStars(selectedRating);
                            });

                            // Hover = preview rating
                            star.addEventListener('mouseenter', () => {
                                updateStars(index + 1);
                            });

                            // Hover keluar = balik ke rating yang dipilih
                            star.addEventListener('mouseleave', () => {
                                updateStars(selectedRating);
                            });
                        });

                        // Inisialisasi kalau udah ada rating
                        updateStars(selectedRating);
                    });
                </script>
            @endpush

        </div>
    @endif

    {{-- Divider --}}
    <hr class="border-outline-variant dark:border-outline-variant-dark"/>

    {{-- Filter Ulasan --}}
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
        ]"/>

                {{-- Filter Sort --}}
                <x-forms.select name="sort" :value="request('sort')" size="sm" :options="[
            'latest' => 'Terbaru',
            'oldest' => 'Terlama',
            'highest' => 'Tertinggi',
            'lowest' => 'Terendah',
        ]"/>

                {{-- Filter Review Teks --}}
                <x-forms.select name="text" :value="request('text')" size="sm" :options="[
            '' => 'Semua Ulasan',
            'yes' => 'Dengan Teks',
            'no' => 'Tanpa Teks',
        ]"/>

            </div>
            <x-buttons.button type="submit" variant="secondary">
                Terapkan Filter
            </x-buttons.button>
        </form>
    </div>


    {{-- Daftar Ulasan --}}
    <div class="space-y-2">
        @forelse ($reviews as $review)
            <div
                class="bg-surface-container-low dark:bg-surface-container-low-dark p-3 rounded-xl shadow-sm border border-outline-variant dark:border-outline-variant-dark space-y-1">
                {{-- Header Review --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-body-sm">
                            {{ $review->user->name ?? 'Anonim' }}
                            @if ($review->user->id && $review->user->id === auth()->user()->id)
                                (kamu)
                            @endif
                        </p>
                        <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                            {{ $review->created_at ? $review->created_at->diffForHumans() : 'Tanggal tidak diketahui.' }}
                            @if ($review->created_at && $review->updated_at->gt($review->created_at))
                                <span class="italic">
                                    (diedit {{ $review->updated_at->diffForHumans() }})
                                </span>
                            @endif
                        </p>
                    </div>
                    {{-- Rating --}}
                    <div class="flex gap-0.5">
                        @for ($j = 1; $j <= 5; $j++)
                            <x-icons.star variant="filled" height="24px"
                                          class="{{ $j <= $review->rating ? 'text-secondary-container dark:text-secondary-container-dark' : 'text-outline-variant dark:text-outline-variant-dark' }}"/>
                        @endfor
                    </div>
                </div>

                {{-- Isi Review --}}
                @if ($review->review)
                    <p class="text-label md:text-body-sm text-justify">
                        {{ $review->review }}
                    </p>
                @endif
            </div>
        @empty
            <p class="text-sm text-center text-on-surface-variant dark:text-on-surface-variant-dark">
                Tidak ada data.
            </p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="flex items-center text-label">
        {{-- Tombol Previous --}}
        <div class="w-1/3 flex justify-start">
            @if (!$reviews->onFirstPage())
                <a href="{{ $reviews->previousPageUrl() }}"
                   class="w-fit flex items-center justify-center rounded-md text-center space-x-0.5 transition px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark">
                    <x-icons.chevron-left/>
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
                    <x-icons.chevron-right/>
                </a>
            @endif
        </div>
    </div>
@endsection
