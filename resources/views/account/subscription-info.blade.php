@extends('layouts.app-account')

@section('title', 'Info Langganan')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Akun Saya', 'url' => route('account.index')],
            ['label' => 'Info Langganan', 'url' => route('account.subscription-info')],
        ]" />
@endsection

@section('account-title', 'Info Langganan')

@section('account-content')
    @if ($currentSubscription)
        <div class="grid grid-cols-2 gap-1 md:gap-2">
            <div>
                <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Jenis Langganan</div>
                {{ ucfirst($currentSubscription->type) }}
            </div>

            <div>
                <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Status Langganan</div>
                {{ $currentSubscription->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </div>

            <div>
                <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Tanggal Mulai</div>
                {{ \Carbon\Carbon::parse($currentSubscription->start_date)->translatedFormat('d F Y') }}
            </div>

            <div>
                <div class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">Tanggal Berakhir</div>
                {{ \Carbon\Carbon::parse($currentSubscription->end_date)->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- TODO: Tambahkan route ke halaman pembelian/pembaruan langganan --}}
        <x-buttons.button href="#">Perbarui Langganan</x-buttons.button>
    @else
        <p class="text-center">Kamu tidak memiliki langganan aktif.</p>
        <x-buttons.button href="#">Mulai Berlangganan</x-buttons.button>
    @endif

    {{-- BAGIAN RIWAYAT LANGGANAN --}}
    @if ($subscriptions->isNotEmpty())
        <h2 class="text-body-md md:text-body-lg font-bold">Riwayat Langganan</h2>
        <table class="w-full border table-fixed">
            <thead
                class="bg-tertiary-container dark:bg-tertiary-container-dark text-on-tertiary-container dark:text-on-tertiary-dark">
                <tr class="text-left font-medium">
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Jenis</th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Tanggal Mulai
                    </th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Tanggal Berakhir
                    </th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $subscription)
                    <tr>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ ucfirst($subscription->type) }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ \Carbon\Carbon::parse($subscription->start_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ \Carbon\Carbon::parse($subscription->end_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ $subscription->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection