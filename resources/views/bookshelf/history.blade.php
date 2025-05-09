@extends('layouts.app-bookshelf')

@section('title', 'Riwayat Baca')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Koleksi Saya', 'url' => route('bookshelf.index')],
            ['label' => 'Riwayat Baca', 'url' => route('bookshelf.history')],
        ]" />
@endsection