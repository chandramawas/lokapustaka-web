@extends('layouts.app-book')

@section('title', $book->title)

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => $book->category->name, 'url' => route('account.index')],
            ['label' => $book->title, 'url' => route('book.detail', $book->id)],
            ['label' => 'Ulasan', 'url' => route('book.reviews', $book->id)],
        ]" />
@endsection

@section('first-content')
    {{-- Cover Buku --}}
    <img src="https://drive.google.com/thumbnail?id=1W4j-hRkaHwqzcFFjDLIgYXxIUoiIvXoC&sz=w1000"
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
            {{ $book->category->name }},
            {{ $book->genres->pluck('name')->join(', ') }}
        </p>
    </div>

    {{-- Overal Rating --}}
    <x-buttons.button variant="custom" icon
        class="w-full shadow-sm hover:shadow-md bg-secondary-container dark:bg-secondary-container-dark text-on-secondary-container dark:text-on-secondary-container-dark pointer-events-none">
        <x-icons.star />
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
                    </p>
                </div>

                {{-- Rating Static (kalau nggak di-edit) --}}
                <div class="flex gap-0.5" x-show="!editMode">
                    @for ($j = 1; $j <= 5; $j++)
                        <x-icons.star variant="filled" height="24px"
                            class="{{ $j <= $userReview->rating ? 'text-secondary-container dark:text-secondary-container-dark' : 'text-outline-variant dark:text-outline-variant-dark' }}" />
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
            <form method="POST" action="{{ route('book.review.update', [$book->id, $userReview->id]) }}" x-show="editMode"
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
                                :data-rating="$i" />
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
                    <x-forms.text-area name="review" rows="3" value="{{ $userReview->review }}" />
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
    @else {{-- Jika Belum Review => Buat Review --}}
        <div
            class="bg-surface dark:bg-surface-dark border border-outline-variant dark:border-outline-variant-dark rounded-xl p-3 space-y-2">
            <form method="POST" action="{{ route('book.review.store', $book->id) }}"
                class="space-y-2 md:space-y-4 text-body-md md:text-body-lg lg:text-body-xl">
                @csrf
                {{-- Rating (bintang) --}}
                <div class="space-y-1">
                    <h4 class="font-medium">Rating</h4>
                    <div class="flex items-center gap-1" id="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-icons.star height="32px" variant="filled"
                                class="star text-outline-variant dark:text-outline-variant-dark cursor-pointer transition-colors duration-150"
                                :data-rating="$i" />
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
                    <x-forms.text-area name="review" placeholder="Bagikan pengalamanmu membaca buku ini..." rows="3" />
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
    <hr class="border-outline-variant dark:border-outline-variant-dark" />

    {{-- Daftar Ulasan --}}
    <div class="space-y-3 max-h-[50vh] overflow-y-auto dropdown-scroll">
        @forelse ($book->reviews as $review)
            <div class="bg-surface-container-low dark:bg-surface-container-low-dark p-3 rounded-xl shadow-sm space-y-1">
                {{-- Header Review --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-body-sm">{{ $review->user->name ?? 'Anonim' }}</p>
                        <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                            {{ $review->created_at->diffForHumans() }}
                        </p>
                    </div>
                    {{-- Rating --}}
                    <div class="flex gap-0.5">
                        @for ($j = 1; $j <= 5; $j++)
                            <x-icons.star variant="filled" height="24px"
                                class="{{ $j <= $review->rating ? 'text-secondary-container dark:text-secondary-container-dark' : 'text-outline-variant dark:text-outline-variant-dark' }}" />
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
                Belum ada ulasan.
            </p>
        @endforelse
    </div>
@endsection