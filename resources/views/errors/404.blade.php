@extends('errors::minimal')

@section('title', __($exception->getMessage() ?: 'Halaman Tidak Ditemukan.'))
@section('code', '404')
@section('message', __($exception->getMessage() ?: 'Halaman Tidak Ditemukan.'))