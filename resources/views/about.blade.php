@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('content')
    {{-- Header / Hero --}}
    <section class="py-14 bg-surface-container text-center px-4">
        <div class="max-w-3xl mx-auto">
            <x-icons.logo class="w-16 h-16 mx-auto text-primary" />
            <h1 class="text-4xl font-bold text-on-surface dark:text-on-surface-dark mt-4">Tentang Lokapustaka</h1>
            <p class="mt-3 text-lg text-on-surface-variant dark:text-on-surface-variant-dark">
                Platform baca buku digital tanpa batas untuk semua pembaca Indonesia.
            </p>
        </div>
    </section>

    {{-- Divider --}}
    <div class="h-px bg-outline dark:bg-outline-dark mx-auto w-24 my-8 rounded-full"></div>

    {{-- Visi Misi --}}
    <section
        class="py-12 px-4 max-w-5xl mx-auto grid md:grid-cols-2 gap-10 text-on-surface-variant dark:text-on-surface-variant-dark">
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Visi</h2>
            <p class="leading-relaxed">
                Menjadi platform digital terbaik untuk membaca buku berbahasa Indonesia, yang dapat diakses kapan saja dan
                di mana saja.
            </p>
        </div>
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Misi</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Menyediakan koleksi buku digital berkualitas dan legal.</li>
                <li>Mempermudah akses membaca untuk semua kalangan.</li>
                <li>Memberi pengalaman membaca yang nyaman dan personal.</li>
            </ul>
        </div>
    </section>

    {{-- Divider --}}
    <div class="h-px bg-outline dark:bg-outline-dark mx-auto w-24 my-8 rounded-full"></div>

    {{-- Kontak --}}
    <section class="py-12 px-4 max-w-3xl mx-auto text-center text-on-surface-variant dark:text-on-surface-variant-dark">
        <h2 class="text-2xl font-semibold mb-6 text-on-surface dark:text-on-surface-dark">Kontak Kami</h2>
        <div class="space-y-3">
            <p>
                <strong>Email:</strong>
                <a href="mailto:support@lokapustaka.com" class="text-primary hover:underline">support@lokapustaka.com</a>
            </p>
            <p><strong>Telepon:</strong> +62 812-3456-7890</p>
            <p><strong>Alamat:</strong> Jl. Literasi No. 42, Jakarta</p>
        </div>
    </section>
@endsection