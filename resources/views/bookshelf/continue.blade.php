@extends('layouts.app-bookshelf')

@section('title', 'Lanjut Baca')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Koleksi Saya', 'url' => route('bookshelf.index')],
            ['label' => 'Lanjut Baca', 'url' => route('bookshelf.continue')],
        ]" />
@endsection