@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
        ]" />
@endsection

@section('content')

@endsection