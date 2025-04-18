<nav
    class="bg-surface-container-low dark:bg-surface-container-low-dark shadow-md text-on-surface dark:text-on-surface-dark px-2 py-2 lg:px-6 flex items-center justify-between">
    {{-- LEFT --}}
    <a href="{{ route('landing') }}" aria-label="Beranda Lokapustaka">
        <x-icons.logo class="h-4" />
    </a>

    {{-- RIGHT --}}
    <div class="flex items-center space-x-2">
        {{-- CHANGE THEME --}}
        <x-buttons.theme-toggle />

        <x-buttons.button :href="route('login')" aria-label="Login Lokapustaka">Masuk</x-ui.button>
    </div>
</nav>