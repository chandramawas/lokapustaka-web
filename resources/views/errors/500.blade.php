@extends('errors::minimal')

@section('title', __($exception->getMessage() ?: 'Kesalahan Server.'))
@section('code', '500')
@section('message', __($exception->getMessage() ?: 'Kesalahan Server.'))