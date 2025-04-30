@extends('layouts.app')

@section('title', 'Checkout Langganan')

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="mx-auto p-4 space-y-4">

        @if (session('status'))
            <div x-data="{ open: true }" class="relative">
                <x-ui.modal showClose>
                    <div class="text-center space-y-3">
                        <h2 class="text-heading-md md:text-heading-lg font-bold text-primary dark:text-primary-dark">
                            {{ session('status_title') }}
                        </h2>
                        <p class="text-body-md text-pretty text-on-surface-variant dark:text-on-surface-variant-dark">
                            {{ session('status_message') }}
                        </p>
                        @if (session('status') === 'success')
                            <x-buttons.button href="{{ route('account.subscription-info') }}" variant="primary-lg" class="w-full">
                                Lihat Info Langganan
                            </x-buttons.button>
                        @elseif (session('status') === 'failed')
                            <x-buttons.button @click="open = false" variant="outline-lg" class="w-full">
                                Kembali
                            </x-buttons.button>
                        @endif
                    </div>
                </x-ui.modal>
            </div>
        @endif

        <h2 class="font-bold text-heading-md md:text-heading-lg lg:text-heading-xl text-center">
            Konfirmasi Langganan
        </h2>

        <div class="rounded-xl max-w-md bg-surface-container dark:bg-surface-container-dark p-5 shadow-lg space-y-3">
            <h3 class="text-heading-md font-bold text-center md:text-start text-primary dark:text-primary-dark">
                {{ $selectedPlan['name'] }}
            </h3>
            <p
                class="text-center md:text-start text-body-lg md:text-body-xl font-bold text-on-surface dark:text-on-surface-dark">
                Rp{{ number_format($selectedPlan['price'], 0, ',', '.') }} <span class="font-normal">/
                    {{ $selectedPlan['duration'] }} hari</span>
            </p>

            @if ($activeSubscription && $activeSubscription->type === $type)
                <p class="text-label text-success text-center md:text-start text-pretty">Kamu akan melanjutkan langganan
                    {{ $type }}mu yang berakhir pada
                    {{ \Carbon\Carbon::parse($activeSubscription->end_date)->translatedFormat('d F Y') }}.
                </p>
            @elseif ($activeSubscription && $activeSubscription->type !== $type)
                <p class="font-bold text-label text-error text-center md:text-start text-pretty">Langganan
                    {{ $activeSubscription->type }}mu yang berakhir pada
                    {{ \Carbon\Carbon::parse($activeSubscription->end_date)->translatedFormat('d F Y') }} akan hangus dan
                    digantikan Paket baru.
                </p>
            @endif

            <form action="{{ route('subscription.pay') }}" method="POST" class="flex flex-col space-y-2 justify-center">
                @csrf
                {{-- Sementara belum pakai payment gateway --}}
                <input type="hidden" name="type" value="{{ $type }}">
                <x-buttons.button type="submit" variant="primary-lg" class="w-full">Bayar Sekarang</x-buttons.button>
            </form>
        </div>
    </div>
@endsection