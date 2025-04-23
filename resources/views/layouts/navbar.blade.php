<nav
    class="bg-surface-container dark:bg-surface-container-dark shadow-md text-on-surface dark:text-on-surface-dark px-3 py-2 lg:px-6 flex items-center justify-between">
    {{-- LEFT --}}
    <div class="flex space-x-3 md:space-x-6 items-center">
        <x-buttons.text-button href="{{ route('home') }}">
            <x-icons.logo />
        </x-buttons.text-button>
        @auth
            {{-- MENU --}}
            <span class="hidden md:flex space-x-3 font-medium">
                <x-buttons.text-button href="#">Menu 1</x-buttons.text-button>
                <x-buttons.text-button href="#">Menu 2</x-buttons.text-button>
                <x-buttons.text-button href="#">Menu 3</x-buttons.text-button>
            </span>
            {{-- HAMBURGER MENU (for MOBILE) --}}
            <div x-data="{ open: false }" class="md:hidden">
                <x-buttons.icon-button variant="text" @click="open = !open">
                    <x-icons.menu />
                </x-buttons.icon-button>
                <x-ui.dropdown>
                    <div class="p-2 space-y-1">
                        <x-buttons.text-button href="#">Menu 1</x-buttons.text-button>
                        <x-buttons.text-button href="#">Menu 2</x-buttons.text-button>
                        <x-buttons.text-button href="#">Menu 3</x-buttons.text-button>
                    </div>
                </x-ui.dropdown>
            </div>
        @endauth
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center space-x-2">
        @auth
            {{-- SEARCH BUTTON --}}
            <div x-data="{ open: false }" class="relative">
                <x-buttons.icon-button variant="text" @click="open = !open">
                    <x-icons.search />
                </x-buttons.icon-button>

                <x-ui.modal>
                    <div class="text-label">
                        <x-forms.input type="text" name="search" placeholder="Cari buku..." leadingIcon required>
                            <x-icons.search />
                        </x-forms.input>
                    </div>
                </x-ui.modal>
            </div>

            {{-- SEARCH BY CATEGORY BUTTON --}}
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                <x-buttons.icon-button variant="text" @click="open = !open">
                    <x-icons.more />
                </x-buttons.icon-button>

                <x-ui.dropdown minWidth="200px" center>
                    <div class="p-2 grid grid-cols-2 gap-2">
                        @for ($category = 1; $category <= 20; $category++)
                            <x-buttons.text-button href="#{{ $category }}" class="break-all">Kategori
                                {{ $category }}</x-buttons.text-button>
                        @endfor
                    </div>
                </x-ui.dropdown>
            </div>

            {{-- ACCOUNT --}}
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                <x-buttons.button variant="primary" icon @click="open = !open">
                    <x-icons.account />
                    <span class="hidden md:flex">
                        {{ auth()->user()->name }}
                    </span>
                </x-buttons.button>

                <x-ui.dropdown minWidth="200px" maxHeight="none" right>
                    {{-- SEC 1 --}}
                    <div
                        class="p-2 space-y-2 bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark">
                        <x-buttons.text-button hoverColor="on-secondary" icon>
                            <x-icons.subscribe />
                            <span>Belum Berlangganan</span>
                        </x-buttons.text-button>
                    </div>

                    <hr class="border-outline-variant dark:border-outline-variant-dark">
                    {{-- SEC 2 --}}
                    <div class="p-2 space-y-2">
                        <x-buttons.text-button icon>
                            <x-icons.bookmark />
                            <span>Disimpan</span>
                        </x-buttons.text-button>

                        <x-buttons.text-button icon>
                            <x-icons.history />
                            <span>Riwayat Baca</span>
                        </x-buttons.text-button>
                    </div>

                    <hr class="border-outline-variant dark:border-outline-variant-dark">
                    {{-- SEC 3 --}}
                    <div class="p-2 space-y-2">
                        <x-buttons.text-button icon>
                            <x-icons.account />
                            <span>Akun Saya</span>
                        </x-buttons.text-button>
                    </div>

                    <hr class="border-outline-variant dark:border-outline-variant-dark">
                    {{-- SEC 4 --}}
                    <div class="p-2 space-y-2">
                        {{-- CHANGE THEME --}}
                        <x-buttons.theme-toggle variant="text" />
                        {{-- LOGOUT BUTTON --}}
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <x-buttons.text-button type="submit" hoverColor="error" icon>
                                <x-icons.logout />
                                <span>Keluar</span>
                            </x-buttons.text-button>
                        </form>
                    </div>
                </x-ui.dropdown>
            </div>
        @endauth

        @guest
            {{-- LOGIN --}}
            <x-buttons.button :href="route('login')" aria-label="Login Lokapustaka">Masuk</x-buttons.button>
            {{-- CHANGE THEME --}}
            <x-buttons.theme-toggle />
        @endguest
    </div>
</nav>