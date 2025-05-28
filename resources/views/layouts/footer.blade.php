<footer
    class="bg-surface-container-lowest dark:bg-surface-container-lowest-dark text-on-surface dark:text-on-surface-dark px-3 py-4">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
        <!-- Left: Logo / Brand -->

        <x-buttons.text-button href="{{ route('home') }}">
            <x-icons.logo />
            <span class="ml-2 flex flex-col">
                <span class="font-medium text-body-md md:text-body-lg">Lokapustaka</span>
                <span class="font-light text-label">Baca Buku Digital Tanpa Batas</span>
            </span>
        </x-buttons.text-button>

        <!-- Center: Links -->
        <div class="flex space-x-4 text-body-sm md:text-body-md">
            <a href="#" class="hover:underline" aria-label="Tentang Lokapustaka">Tentang</a>
            <a href="#" class="hover:underline" aria-label="Kontak Lokapustaka">Kontak</a>
            <a href="#" class="hover:underline" aria-label="Kebijakan Lokapustaka">Kebijakan</a>
            @guest
                <a href="{{ route('filament.admin.auth.login') }}" class="hover:underline"
                    aria-label="Login sebagai Admin Lokapustaka">Login Admin</a>
            @endguest
        </div>

        <!-- Right: Copyright -->
        <div class="text-label">
            &copy; 2025 Lokapustaka. All rights reserved.
        </div>
    </div>
</footer>