<nav
    class="bg-surface-container-low dark:bg-surface-container-low-dark shadow-md text-on-surface dark:text-on-surface-dark px-2 py-2 lg:px-6 flex items-center justify-between">
    {{-- LEFT --}}
    <a href="{{ route('home') }}" aria-label="Beranda Lokapustaka">
        <x-icon.logo class="h-4" />
    </a>

    {{-- RIGHT --}}
    <div class="flex items-center space-x-2">
        {{-- CHANGE THEME --}}
        <x-ui.theme-toggle />

        <x-ui.button :href="route('login')" aria-label="Login Lokapustaka">Masuk</x-ui.button>
    </div>
</nav>