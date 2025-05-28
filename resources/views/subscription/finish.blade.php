@extends('layouts.app')

@section('title')
    @if ($orderData->status === 'completed')
        Pembayaran Selesai
    @elseif ($orderData->status === 'pending')
        Pembayaran Sedang Diproses
    @else
        Pembayaran Gagal
    @endif
@endsection
@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="flex flex-col gap-3 p-3 min-h-[calc(100vh-4rem)] justify-center items-center">
        <div class="space-y-1">
            <h2
                class="font-bold text-heading-sm md:text-heading-md lg:text-heading-lg text-center {{ $orderData->status === 'completed' ? 'text-primary dark:text-primary-dark' : 'text-error dark:text-error-dark' }}">
                @if ($orderData->status === 'completed')
                    Pembayaran Selesai
                @elseif ($orderData->status === 'pending')
                    Pembayaran Sedang Diproses
                @else
                    Pembayaran Gagal
                @endif
            </h2>
            <p class="text-body-sm md:text-body-md text-center text-pretty max-w-2xl">
                @if ($orderData->status === 'completed')
                    Terima kasih telah melakukan pembayaran! Berikut adalah detail pembayaranmu.
                @elseif ($orderData->status === 'pending')
                    Pembayaranmu sedang dalam proses. Silakan tunggu beberapa saat untuk konfirmasi lebih lanjut.
                @else
                    Maaf, pembayaranmu tidak berhasil. Silakan coba lagi atau hubungi dukungan kami jika ada masalah.
                @endif
            </p>
        </div>

        <div
            class="max-w-xl mx-auto grid grid-cols-2 p-3 md:p-4 gap-3 md:gap-4 rounded-xl shadow-lg bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
            {{-- Informasi Pembayaran --}}
            <div class="space-y-2 text-body-md md:text-body-lg {{ !$orderData->subscription ? 'col-span-2' : '' }}">
                <div>
                    <h4 class="font-semibold">Order ID</h4>
                    <p>{{ $orderData->order_id }}</p>
                </div>
                <div>
                    <h4 class="font-semibold">Metode Pembayaran</h4>
                    <p>
                        {{ match ($orderData->method) { 'bank_transfer' => 'Virtual Account', 'qris' => 'QRIS', 'cstore' => 'Alfamart', 'manual' => 'Manual', default => 'Tidak diketahui'} }}
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold">Status</h4>
                    @if($orderData->status === 'completed')
                        <p class="text-success dark:text-success-dark">Berhasil</p>
                    @elseif ($orderData->status === 'pending')
                        <p class="text-warning dark:text-warning-dark">Pending</p>
                    @else
                        <p class="text-error dark:text-error-dark">Gagal</p>
                    @endif
                </div>
                <div>
                    <h4 class="font-semibold">Total Pembayaran</h4>
                    <p>
                        Rp{{ number_format($orderData->amount, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold">Tanggal Bayar</h4>
                    <p>
                        {{ $orderData->paid_at ? \Carbon\Carbon::parse($orderData->paid_at)->translatedFormat('d F Y H:i') : '-' }}
                    </p>
                </div>
            </div>

            {{-- Info Langganan (Subscription) --}}
            @if ($orderData->subscription)
                <div class="space-y-2 text-body-md md:text-body-lg">
                    <div>
                        <h4 class="font-semibold">Paket</h4>
                        <p>{{ ucfirst($orderData->subscription->type) }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold">Status</h4>
                        @if($orderData->subscription->is_active)
                            <p class="text-primary dark:text-primary-dark">Aktif</p>
                        @else
                            <p>Tidak Aktif</p>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-semibold">Masa Aktif</h4>
                        {{ \Carbon\Carbon::parse($orderData->subscription->start_date)->translatedFormat('d F Y') }}
                        -
                        {{ \Carbon\Carbon::parse($orderData->subscription->end_date)->translatedFormat('d F Y') }}
                    </div>
                </div>
            @endif
        </div>

        <div class="flex gap-2">
            <x-buttons.button :href="route('home')" variant="secondary">
                Kembali ke Beranda
            </x-buttons.button>
            @if ($orderData->status === 'completed')
                <x-buttons.button :href="route('account.subscription-info')" variant="primary">
                    Info Langganan
                </x-buttons.button>
            @elseif ($orderData->status === 'pending')
                <x-buttons.button :href="route('account.payment-history')" variant="primary">
                    Cek Riwayat Pembayaran
                </x-buttons.button>
            @else
                <x-buttons.button :href="route('subscription.index')" variant="primary">
                    Coba Lagi
                </x-buttons.button>
            @endif
        </div>
    </div>
@endsection