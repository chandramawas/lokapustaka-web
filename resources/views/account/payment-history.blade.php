@extends('layouts.app-account')

@section('title', 'Riwayat Pembayaran')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Akun Saya', 'url' => route('account.index')],
            ['label' => 'Riwayat Pembayaran', 'url' => route('account.payment-history')],
        ]" />
@endsection

@section('account-title', 'Riwayat Pembayaran')

@section('account-content')
    {{-- BAGIAN RIWAYAT LANGGANAN --}}
    @if ($payments->isNotEmpty())
        <table class="w-full border">
            <thead
                class="bg-tertiary-container dark:bg-tertiary-container-dark text-on-tertiary-container dark:text-on-tertiary-dark">
                <tr class="font-medium">
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">ID</th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">SUB_ID
                    </th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Jumlah Pembayaran
                    </th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Metode</th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Status</th>
                    <th class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">Tanggal
                        Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ $payment->id }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ $payment->subscription_id }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ ucfirst($payment->method) }}
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            @if($payment->status === 'completed')
                                <span class="text-success dark:text-success-dark font-medium">Berhasil</span>
                            @elseif ($payment->status === 'pending')
                                <span class="text-warning dark:text-warning-dark font-medium">Pending</span>
                            @else
                                <span class="text-error dark:text-error-dark font-medium">Gagal</span>
                            @endif
                        </td>
                        <td class="border border-on-surface-variant dark:border-on-surface-variant-dark py-1 px-2">
                            {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center text-on-surface-variant dark:text-on-surface-variant-dark py-4">
            Belum ada riwayat pembayaran.
        </div>
    @endif
@endsection