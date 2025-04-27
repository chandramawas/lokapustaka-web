@extends('layouts.app-account')

@section('title', 'Akun Saya')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Akun Saya', 'url' => route('account.index')],
        ]" />
@endsection

@section('account-title', 'Akun Saya')

@section('account-content')
    <div>
        <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Nama</div>
        {{ $user->name ?? '-' }}
    </div>

    <div>
        <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Email</div>
        {{ $user->email ?? '-' }}
    </div>

    <div>
        <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Jenis Kelamin</div>
        {{ $user->gender ?? '-' }}
    </div>

    <div>
        <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Tanggal Lahir
        </div>
        {{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->translatedFormat('d F Y') : '-' }}
    </div>

    <div>
        <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Langganan</div>
        {{ $currentSubscription ? ucfirst($currentSubscription->type) . ' - Berlaku hingga ' . \Carbon\Carbon::parse($currentSubscription->end_date)->translatedFormat('d F Y') : '-'  }}
    </div>
@endsection