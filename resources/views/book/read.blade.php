@extends('layouts.app')

@section('title', "Membaca " . $book->title)

@section('content')
    <div x-data="{ tocOpen: false }" class="w-full h-screen flex flex-col">
        {{-- TOP BAR --}}
        <div
            class="text-body-md flex gap-3 items-center justify-between p-2 sticky top-0 z-50 bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark shadow-md">
            {{-- Kiri: Back Button + Judul --}}
            <div class="flex items-center gap-2">
                <x-buttons.text-button :href="route('book.detail', $book->slug)">
                    <x-icons.arrow-back />
                </x-buttons.text-button>
                <h1 class="font-medium line-clamp-1 break-all">
                    {{ $book->title }}
                </h1>
                {{-- Progress Text --}}
                <div id="reading-progress-text"
                    class='text-label flex space-x-0.5 shadow-md w-fit px-1 py-0.5 rounded-md justify-center items-center'>
                </div>
            </div>

            {{-- Kanan: Opsi/Opsi lainnya (dropdown/modal/whatever) --}}
            <div class="flex gap-2">
                {{-- GANTI FONT SIZE --}}
                {{-- Kurangkan ukuran --}}
                <x-buttons.text-button id="decrease-font">
                    <x-icons.text-decrease />
                </x-buttons.text-button>

                {{-- Naikkan ukuran --}}
                <x-buttons.text-button id="increase-font">
                    <x-icons.text-increase />
                </x-buttons.text-button>

                <div class="w-[1px] h-auto bg-on-surface dark:bg-on-surface-dark"></div>

                {{-- TOC Button --}}
                <x-buttons.text-button @click="tocOpen = true">
                    <x-icons.menu />
                </x-buttons.text-button>

                {{-- Settings --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <x-buttons.text-button @click="open = !open">
                        <x-icons.setting />
                    </x-buttons.text-button>

                    <x-ui.dropdown minWidth="200px" maxHeight="none" right>
                        {{-- SEC 1 --}}
                        <div class="px-1 py-2 flex flex-col gap-2 items-center">
                            <div class="flex gap-1 justify-between">
                                {{-- GANTI FONT STYLE --}}
                                <div class="gap-0.5 flex flex-col">
                                    <p class="text-center">Gaya Teks</p>
                                    <div class="flex justify-center gap-1 w-full">
                                        {{-- SERIF --}}
                                        <x-buttons.icon-button id="serif" variant="outline">
                                            <x-icons.font-serif />
                                        </x-buttons.icon-button>

                                        {{-- SANS SERIF --}}
                                        <x-buttons.icon-button id="sans-serif" variant="outline">
                                            <x-icons.font-sans-serif />
                                        </x-buttons.icon-button>
                                    </div>
                                </div>

                                {{-- PAGE FLOW --}}
                                <div class="gap-0.5 flex flex-col">
                                    <p class="text-center">Alur Halaman</p>
                                    <div class="flex justify-center gap-1 w-full">
                                        {{-- Scroll --}}
                                        <x-buttons.icon-button id="flow-scrolled" variant="outline">
                                            <x-icons.scrollable />
                                        </x-buttons.icon-button>

                                        {{-- Per Page --}}
                                        <x-buttons.icon-button id="flow-paginated" variant="outline">
                                            <x-icons.per-page />
                                        </x-buttons.icon-button>
                                    </div>
                                </div>
                            </div>
                            {{-- COLOR THEME --}}
                            <div class="gap-0.5 flex flex-col">
                                <p class="text-center">Tema Baca</p>
                                <div class="flex justify-center gap-1 w-full">
                                    {{-- DAY --}}
                                    <x-buttons.icon-button data-theme="day" variant="outline">
                                        <x-icons.theme variant="light" />
                                    </x-buttons.icon-button>

                                    {{-- NIGHT --}}
                                    <x-buttons.icon-button data-theme="night" variant="outline">
                                        <x-icons.theme variant="dark" />
                                    </x-buttons.icon-button>

                                    {{-- SEPIA --}}
                                    <x-buttons.icon-button data-theme="sepia" variant="outline">
                                        <x-icons.history-edu />
                                    </x-buttons.icon-button>

                                    {{-- B&W --}}
                                    <x-buttons.icon-button data-theme="bw" variant="outline">
                                        <x-icons.contrast />
                                    </x-buttons.icon-button>
                                </div>
                            </div>

                            {{-- ADVANCE SETTING --}}
                            <x-buttons.text-button onclick="alert('Coming Soon')" underlineHover
                                class="text-on-surface-variant dark:text-on-surface-variant-dark">
                                Advance Settings
                            </x-buttons.text-button>
                        </div>

                        <hr class="border-outline-variant dark:border-outline-variant-dark">
                        {{-- SEC 2 --}}
                        <div class="p-2 space-y-2">
                            {{-- Cari BUKU --}}
                            {{-- <x-buttons.text-button icon>
                                <x-icons.search />
                                <span>Cari</span>
                            </x-buttons.text-button> --}}

                            {{-- FULLSCREEN --}}
                            <x-buttons.text-button id="toggle-fullscreen" icon>
                                <x-icons.fullscreen />
                                <span>Layar Penuh</span>
                            </x-buttons.text-button>
                        </div>

                        <hr class="border-outline-variant dark:border-outline-variant-dark">
                        {{-- SEC 3 --}}
                        <div class="p-2 space-y-2">
                            {{-- DETAIL BUKU --}}
                            <x-buttons.text-button :href="route('book.detail', $book->slug)" icon>
                                <x-icons.information />
                                <span>Detail Buku</span>
                            </x-buttons.text-button>

                            {{-- REVIEW BUKU --}}
                            <x-buttons.text-button :href="route('book.reviews', $book->slug)" icon>
                                <x-icons.star />
                                <span>Ulas</span>
                            </x-buttons.text-button>
                        </div>

                        <hr class="border-outline-variant dark:border-outline-variant-dark">
                        {{-- SEC 4 --}}
                        <div class="p-2 space-y-2">
                            {{-- CHANGE THEME --}}
                            <x-buttons.theme-toggle variant="text" />
                        </div>
                    </x-ui.dropdown>
                </div>
            </div>
        </div>


        {{-- MAIN CONTENT --}}
        <div id="reader-parent" class="flex size-full overflow-hidden mb-1">
            {{-- TOC Drawer --}}
            <div x-show="tocOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 z-[60] w-[240px] shadow-md p-2 overflow-y-auto dropdown-scroll space-y-1 bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
                <x-buttons.icon-button variant="text" @click="tocOpen = false">
                    <x-icons.chevron-right />
                </x-buttons.icon-button>
                <div class="px-2 space-y-1">
                    <h2 class="text-body-xl font-bold">Daftar Isi</h2>
                    <ul class="toc-list space-y-0.5 text-body-md">
                        <p class="text-center text-on-surface-dark animate-pulse">
                            Memuat daftar isi...
                        </p>
                    </ul>
                </div>
            </div>
            {{-- Overlay gelap saat TOC drawer dibuka --}}
            <div x-show="tocOpen" @click="tocOpen = false" class="fixed inset-0 bg-shadow/50 z-50">
            </div>

            <div id="book-loading" class="fixed inset-0 bg-shadow/90 z-40 flex justify-center items-center">
                <p class="text-center text-on-surface-dark animate-pulse">
                    Memuat buku...
                </p>
            </div>

            {{-- Reader Area --}}
            <main class="flex flex-col size-full overflow-hidden p-1 gap-0.5">
                {{-- TOC Button --}}
                <div class="justify-end" id="fullscreen-toc-button" style="display: none">
                    <x-buttons.icon-button @click="tocOpen = true">
                        <x-icons.menu />
                    </x-buttons.icon-button>
                </div>

                <x-buttons.button variant="outline" id="prev-page">
                    <x-icons.chevron-left />
                    <span>Sebelumnya</span>
                </x-buttons.button>

                <div id="reader" data-epub="{{ $epubUrl }}" data-book="{{ $book->id }}" data-cfi="{{ $lastCfi }}"
                    class="size-full">
                </div>

                <x-buttons.button variant="outline" id="next-page">
                    <span>Selanjutnya</span>
                    <x-icons.chevron-right />
                </x-buttons.button>

                <div id="rating-button" class="w-full h-fit hidden">
                    <x-buttons.rating-button :book="$book" />
                </div>
            </main>

            {{-- BOTTOM: Progress Indicator --}}
            <div class="fixed z-30 bottom-0 inset-x-0 h-1 bg-surface dark:bg-surface-dark">
                <div id="reading-progress-bar" class="size-full transition-all duration-500" style="width: 0%;">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer', '')

@push('styles')
    <style>
        .epub-container {
            overflow-x: hidden !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const readerParent = document.getElementById('reader-parent');
            const fullscreenBtn = document.getElementById('toggle-fullscreen');
            const fullscreenTocBtn = document.getElementById('fullscreen-toc-button');
            const progressText = document.getElementById('reading-progress-text');

            // Toggle fullscreen mode
            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    readerParent.requestFullscreen().catch(err => {
                        alert(`Error trying to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
            });

            // When entering fullscreen, hide the progress text
            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement) {
                    // Hide progress text in fullscreen mode
                    progressText.style.display = 'none';
                    fullscreenTocBtn.style.display = 'flex';
                } else {
                    // Show progress text when exiting fullscreen
                    progressText.style.display = 'block';
                    fullscreenTocBtn.style.display = 'none';
                }
            });
        });
    </script>
@endpush