{{--
|-------------------------------------------------------------------------------------
| Layout: App Account Layout
|--------------------------------------------------------------------------------------
| Deskripsi:
| Layout ini adalah template dasar untuk semua halaman "Akun" di aplikasi Lokapustaka.
|--------------------------------------------------------------------------------------
| Cara Pakai:
| @extends('layouts.app-account')
|
| Bagian yang bisa diisi:
|
| 1. @section('title', 'Judul Halaman')
| - Akan menjadi judul tab di browser.
| - Jika tidak diisi, default-nya adalah 'Akun Saya'.
|
| 2. @section('navbar', view('layouts.navbar'))
| - Untuk mengganti navbar yang ditampilkan di atas halaman.
| - Opsional. Jika tidak diisi, navbar default akan digunakan.
|
| 3. @section('breadcrumbs')
| - Untuk menampilkan breadcrumb navigation di atas konten.
| - Opsional. Jika tidak diisi, breadcrumbs tidak akan tampil.
|
| 4. @section('account-title', 'Judul Akun')
| - Untuk judul utama di bagian konten akun.
| - Wajib diisi untuk setiap halaman akun.
|
| 5. @section('account-content')
| - Untuk konten utama halaman akun.
| - Wajib diisi untuk setiap halaman akun.
|--------------------------------------------------------------------------------------}}


@extends('layouts.app')

@section('title', 'Akun Saya')

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="flex space-x-2 md:space-x-3 p-2 md:p-3">
        <aside
            class="max-h-fit md:w-1/5 p-1 md:p-2 md:space-y-2 rounded-lg shadow bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
            <x-ui.sidebar activeColor="secondary" :items="[
            [
                'label' => 'Akun Saya',
                'url' => 'account.index',
                'icon' => 'icons.account',
            ],
            [
                'label' => 'Pengaturan Akun',
                'url' => 'account.settings',
                'icon' => 'icons.account-setting',
            ],
            [
                'label' => 'Ubah Kata Sandi',
                'url' => 'account.change-password',
                'icon' => 'icons.password',
            ],
            [
                'label' => 'Info Langganan',
                'url' => 'account.subscription',
                'icon' => 'icons.subscribe',
            ],
        ]" />
        </aside>

        <div class="flex-1 flex flex-col space-y-2">
            <section id="breadcrumbs">
                @yield('breadcrumbs')
            </section>
            <section id="content"
                class="p-2 md:p-3 space-y-1 md:space-y-2 rounded-lg shadow bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
                <div class="font-bold text-heading-sm md:text-heading-md">
                    @yield('account-title', 'Akun Saya')
                </div>
                <div class="text-body-sm md:text-body-md space-y-2">
                    @yield('account-content')
                </div>
            </section>
        </div>
    </div>
@endsection