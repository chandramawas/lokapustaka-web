<nav
    class="bg-surface-container dark:bg-surface-container-dark shadow-md text-on-surface dark:text-on-surface-dark px-3 py-2 lg:px-5 flex items-center justify-between">
    {{-- LEFT --}}
    <div class="flex space-x-2 lg:space-x-5 items-center">
        <x-buttons.text-button href="{{ route('home') }}">
            <x-icons.logo />
        </x-buttons.text-button>
        @auth
            {{-- MENU --}}
            <span class="hidden md:flex space-x-3 font-medium">
                <x-buttons.text-button :href="route('book.collection')">Semua Koleksi</x-buttons.text-button>
                <x-buttons.text-button :href="route('bookshelf.index')">Koleksi Saya</x-buttons.text-button>
                <x-buttons.text-button :href="route('account.subscription-info')">Info Langganan</x-buttons.text-button>
            </span>
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
                        <form action="{{ route('book.collection') }}" method="get">
                            <x-forms.input type="text" name="q" placeholder="Cari buku..." value="{{ request('q') }}"
                                leadingIcon required>
                                <x-icons.search />
                            </x-forms.input>
                        </form>
                    </div>
                </x-ui.modal>
            </div>

            {{-- SEARCH BY GENRE BUTTON --}}
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                <x-buttons.icon-button variant="text" @click="open = !open">
                    <x-icons.more />
                </x-buttons.icon-button>

                <x-ui.dropdown minWidth="300px" right>
                    <div class="p-2 grid grid-cols-3 gap-2">
                        @foreach($genres as $genre)
                            <x-buttons.text-button :href="route('book.genre.collection', $genre->slug)" class="break-all">
                                {{ $genre->name }}
                            </x-buttons.text-button>
                        @endforeach
                    </div>
                </x-ui.dropdown>
            </div>

            {{-- ACCOUNT --}}
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                <x-buttons.button variant="primary" icon @click="open = !open">
                    <x-icons.account />
                    <span class="hidden md:block truncate max-w-[150px]">
                        {{ auth()->user()->name }}
                    </span>
                </x-buttons.button>

                <x-ui.dropdown minWidth="200px" maxHeight="none" right>
                    {{-- SEC 1 --}}
                    @if (auth()->user()->activeSubscription() && auth()->user()->activeSubscription()->is_active)
                        @if (\Carbon\Carbon::now()->diffInDays(auth()->user()->activeSubscription()->end_date, false) <= 6 && \Carbon\Carbon::now()->diffInDays(auth()->user()->activeSubscription()->end_date, false) > 0)
                            {{-- Warning: Sisa sedikit --}}
                            <a href="{{ route('account.subscription-info') }}"
                                class="flex space-x-0.5 p-2 items-center bg-warning-container dark:bg-warning-container-dark text-on-warning-container dark:text-on-warning-container-dark hover:bg-warning-container/80 dark:hover:bg-warning-container-dark/80 hover:text-on-warning-container/80 dark:hover:text-on-warning-container-dark/80">
                                <x-icons.subscribe />
                                <span class="flex flex-col">
                                    <span class="font-bold">Berlangganan</span>
                                    <span>Sisa
                                        {{ \Carbon\Carbon::now()->diffInDays(auth()->user()->activeSubscription()->end_date, false) }}
                                        Hari lagi</span>
                                </span>
                            </a>
                        @else
                            {{-- Berlangganan --}}
                            <a href="{{ route('account.subscription-info') }}"
                                class="flex space-x-0.5 p-2 items-center bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark hover:bg-secondary/80 dark:hover:bg-secondary-dark/80 hover:text-on-secondary/80 dark:hover:text-on-secondary-dark/80">
                                <x-icons.subscribe />
                                <span class="flex flex-col">
                                    <span class="font-bold">Berlangganan</span>
                                    <span>Aktif Sampai
                                        {{ \Carbon\Carbon::parse(auth()->user()->activeSubscription()->end_date)->translatedFormat('d/m/y') }}
                                    </span>
                                </span>
                            </a>
                        @endif
                    @else
                        {{-- Tidak Berlangganan --}}
                        <a href="{{ route('subscription.index') }}"
                            class="flex space-x-0.5 p-2 bg-tertiary dark:bg-tertiary-dark text-on-tertiary dark:text-on-tertiary-dark hover:bg-tertiary/80 dark:hover:bg-tertiary-dark/80 hover:text-on-tertiary/80 dark:hover:text-on-tertiary-dark/80">
                            <x-icons.subscribe />
                            <span>Tidak Berlangganan</span>
                        </a>
                    @endif

                    <hr class="border-outline-variant dark:border-outline-variant-dark">
                    {{-- SEC 2 --}}
                    <div class="p-2 space-y-2">
                        <x-buttons.text-button :href="route('bookshelf.index')" icon>
                            <x-icons.bookmark />
                            <span>Koleksi Saya</span>
                        </x-buttons.text-button>
                    </div>

                    <hr class="border-outline-variant dark:border-outline-variant-dark">
                    {{-- SEC 3 --}}
                    <div class="p-2 space-y-2">
                        @if (!auth()->user()->hasVerifiedEmail())
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <x-buttons.text-button type="submit" hoverColor="primary" icon>
                                    <x-icons.account />
                                    <span>Verifikasi Email</span>
                                </x-buttons.text-button>
                            </form>
                            @if (session('new-verif-link'))
                                <x-forms.label variant="success" textAlign="left">
                                    Link verifikasi baru sudah dikirim ke email.
                                </x-forms.label>
                            @endif
                        @else
                            <x-buttons.text-button icon :href="route('account.index')">
                                <x-icons.account />
                                <span>Akun Saya</span>
                            </x-buttons.text-button>
                        @endif
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