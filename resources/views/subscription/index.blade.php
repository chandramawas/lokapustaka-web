@extends('layouts.app')

@section('title', 'Paket Langganan')

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="flex flex-col space-y-4 p-3 min-h-[calc(100vh-4rem)] justify-center items-center">
        {{-- Title --}}
        <div class="max-w-3xl mx-auto space-y-2">
            <h2 class="font-bold text-heading-md md:text-heading-lg lg:text-heading-xl text-center">
                Pilih Paket Langganan
            </h2>
            <p
                class="text-body-md md:text-body-lg text-center text-pretty text-on-surface-variant dark:text-on-surface-variant-dark">
                Pilih paket langganan yang sesuai dengan kebutuhan Anda dan nikmati berbagai fitur premium yang kami
                tawarkan.
            </p>
        </div>
        {{-- Subscription Plan --}}
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
            {{-- Bulanan --}}
            <section id="monthlySubscription"
                class="p-5 space-y-3 rounded-xl shadow-lg bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark overflow-hidden">
                {{-- Judul --}}
                <div class="space-y-1 text-center md:text-start">
                    <h2 class="font-medium text-body-md text-primary dark:text-primary-dark">Bulanan</h2>
                    <p class="font-bold text-display-md">Rp20.000<span
                            class="font-medium text-body-md text-on-surface-variant dark:text-on-surface-variant-dark">/bulan</span>
                    </p>
                </div>

                {{-- Deskripsi --}}
                <p class="text-body-md text-pretty text-center">
                    Nikmati akses penuh ke semua fitur premium dengan paket bulanan yang fleksibel dan terjangkau.
                </p>

                {{-- Button --}}
                @if ($activeSubscription && $activeSubscription->type === 'bulanan')
                    <x-buttons.button :href="route('subscription.checkout', 'bulanan')" variant="primary-lg">Perpanjang
                        Paket</x-buttons.button>
                @elseif ($activeSubscription && $activeSubscription->type === 'tahunan')
                    <x-buttons.button :href="route('subscription.checkout', 'bulanan')" variant="secondary-lg">Ganti
                        Paket</x-buttons.button>
                @else
                    <x-buttons.button :href="route('subscription.checkout', 'bulanan')" variant="primary-lg">Pilih
                        Paket</x-buttons.button>
                @endif
            </section>

            {{-- Tahunan --}}
            <section id="yearlySubscription"
                class="p-5 space-y-3 rounded-xl shadow-lg bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark overflow-hidden">
                {{-- Judul --}}
                <div class="space-y-1 text-center md:text-start">
                    <h2 class="font-medium text-body-md text-primary dark:text-primary-dark">Tahunan</h2>
                    <p class="font-bold text-display-md">Rp75.000<span
                            class="font-medium text-body-md text-on-surface-variant dark:text-on-surface-variant-dark">/tahun</span>
                    </p>
                </div>

                {{-- Deskripsi --}}
                <p class="text-body-md text-pretty text-center">
                    Paket tahunan yang lebih hemat hingga 69%, jadi hanya Rp6.000 per bulan.
                </p>

                {{-- Button --}}
                @if ($activeSubscription && $activeSubscription->type === 'tahunan')
                    <x-buttons.button :href="route('subscription.checkout', 'tahunan')" variant="primary-lg">Perpanjang
                        Paket</x-buttons.button>
                @elseif ($activeSubscription && $activeSubscription->type === 'bulanan')
                    <x-buttons.button :href="route('subscription.checkout', 'tahunan')" variant="secondary-lg">Ganti
                        Paket</x-buttons.button>
                @else
                    <x-buttons.button :href="route('subscription.checkout', 'tahunan')" variant="primary-lg">Pilih
                        Paket</x-buttons.button>
                @endif
            </section>

        </div>

        <div class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl" aria-hidden="true">
            <div class="mx-auto aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-secondary to-primary opacity-30"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
    </div>
@endsection