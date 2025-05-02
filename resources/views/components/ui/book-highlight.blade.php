@props(['sectionName' => null, 'badges' => [], 'id' => '1', 'author' => 'Penulis', 'title' => 'Judul', 'description' => 'Deskripsi tidak tersedia.', 'poster' => 'https://placehold.co/150x220?text=Poster+not+available.'])

@php

@endphp

<section id="{{ $sectionName ?? '' }}" class="relative overflow-hidden h-[250px] md:h-[300px]">
    <svg class="absolute -z-10" xmlns="http://www.w3.org/2000/svg" version="1.1"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev/svgjs" width="1440" height="300"
        preserveAspectRatio="none" viewBox="0 0 1440 250">
        <g mask="url(&quot;#SvgjsMask1000&quot;)" fill="none">
            <rect width="1440" height="250" x="0" y="0" fill="#0e2a47"></rect>
            <path d="M37 250L287 0L573.5 0L323.5 250z" fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M248.60000000000002 250L498.6 0L696.6 0L446.6 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M509.20000000000005 250L759.2 0L1014.2 0L764.2 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M715.8000000000001 250L965.8000000000001 0L1214.8000000000002 0L964.8000000000001 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M1416 250L1166 0L1014.5 0L1264.5 250z" fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M1192.4 250L942.4000000000001 0L772.9000000000001 0L1022.9000000000001 250z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M929.8 250L679.8 0L411.29999999999995 0L661.3 250z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M682.1999999999999 250L432.19999999999993 0L154.69999999999993 0L404.69999999999993 250z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M1293.254318036348 250L1440 103.25431803634794L1440 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M0 250L146.74568196365206 250L 0 103.25431803634794z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
        </g>
        <defs>
            <mask id="SvgjsMask1000">
                <rect width="1440" height="250" fill="#ffffff"></rect>
            </mask>
            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%" id="SvgjsLinearGradient1001">
                <stop stop-color="rgba(15, 70, 185, 0.2)" offset="0"></stop>
                <stop stop-opacity="0" stop-color="rgba(15, 70, 185, 0.2)" offset="0.66"></stop>
            </linearGradient>
            <linearGradient x1="100%" y1="100%" x2="0%" y2="0%" id="SvgjsLinearGradient1002">
                <stop stop-color="rgba(15, 70, 185, 0.2)" offset="0"></stop>
                <stop stop-opacity="0" stop-color="rgba(15, 70, 185, 0.2)" offset="0.66"></stop>
            </linearGradient>
        </defs>
    </svg>
    <div
        class="size-full py-4 px-4 md:px-8 flex gap-4 md:gap-8 justify-between backdrop-blur-sm backdrop-brightness-75 text-on-surface-dark bg-gradient-to-t from-shadow/80 to-transparent">
        {{-- Deskripsi --}}
        <div class="flex flex-col justify-between w-full">
            <div class="space-y-1 md:space-y-2">
                {{-- Badge --}}
                @if ($badges)
                    <div class="flex gap-1">
                        @foreach ($badges as $badge)
                            <x-ui.badge color="{{ $badge['color'] }}">{{ $badge['content'] }}</x-ui.badge>
                        @endforeach
                    </div>
                @endif
                {{-- Deskripsi --}}
                <div class="space-y-0.5">
                    <h3 class="font-medium text-body-sm lg:text-body-lg text-on-surface-variant-dark line-clamp-1">
                        {{ $author }}
                    </h3>
                    <h2 class="font-bold text-heading-sm md:text-heading-lg line-clamp-1">
                        {{ $title }}
                    </h2>
                    <p class="text-label text-pretty text-on-surface-variant-dark line-clamp-2 md:line-clamp-3">
                        {{ $description }}
                    </p>
                </div>
            </div>
            {{-- Button --}}
            <div class="flex gap-1">
                <x-buttons.button href="#" variant="primary" class="w-full">Baca Sekarang</x-buttons.button>
                <x-buttons.button href="#" variant="custom" icon
                    class="shadow-sm hover:shadow-md bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark hover:bg-secondary/80 dark:hover:bg-secondary-dark/80 hover:text-on-secondary/80 dark:hover:text-on-secondary-dark/80">
                    <x-icons.star /><span>4.7</span>
                </x-buttons.button>
                <x-buttons.icon-button href="#" variant="secondary"><x-icons.add /></x-buttons.icon-button>
                <x-buttons.icon-button :href="route('book.detail', $id)"
                    variant="secondary"><x-icons.information /></x-buttons.icon-button>
            </div>
        </div>
        {{-- Poster --}}
        <img src="{{ $poster }}" loading="lazy" class="rounded-md object-cover">
    </div>
</section>