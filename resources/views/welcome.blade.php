<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <title>WOEngineer</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="icon" href="{{ asset('favicon.ico') }}"> --}}
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body
    class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased min-h-screen flex flex-col transition-colors duration-300"
    x-data="{
        darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        }
    }">
    <x-loading-screen />
    {{-- NAVBAR --}}
    <nav x-data="{ mobileMenuOpen: false, profileOpen: false }"
        class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-300 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">

                {{-- Mobile Menu --}}
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-500 dark:hover:text-white focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Logo --}}
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <img class="h-10 w-auto" src="{{ asset('assets/logo.png') }}" alt="Logo">
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <a href="#"
                                class="rounded-md bg-gray-100 dark:bg-gray-900 px-3 py-2 text-sm font-medium text-gray-900 dark:text-white transition-colors">Home</a>
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">Dashboard</a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Right Buttons --}}
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 gap-3">
                    <button @click="toggleTheme()" type="button"
                        class="rounded-lg p-2.5 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-colors">
                        <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>

                    @auth
                        <div class="relative ml-3">
                            <button @click="profileOpen = !profileOpen" type="button"
                                class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=6366f1&color=fff"
                                    alt="">
                            </button>
                            <div x-show="profileOpen" @click.away="profileOpen = false"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                style="display: none;">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Log
                                        out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex space-x-3">
                            <a href="{{ route('login') }}"
                                class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Log
                                in</a>
                            <a href="{{ route('register') }}"
                                class="rounded-md border border-gray-300 dark:border-gray-600 px-3.5 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow p-6">
        <div class="w-full max-w-7xl mx-auto">

            {{-- HEADER SECTION --}}
            <div class="text-center mb-10 mt-4">
                <h1
                    class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl transition-colors">
                    Work Order <span class="text-indigo-600 dark:text-indigo-500">Engineering</span>
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 transition-colors">
                    Live report. Track the status of your engineering work orders in real-time.
                </p>
            </div>

            {{-- 
                BAGIAN 1: STATISTIK CARDS (HANYA ANGKA) 
                Ini sesuai permintaan: Card di atas hanya menampilkan jumlah.
            --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border-l-4 border-indigo-500 transition-all hover:scale-105">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ticket</p>
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">
                                {{ $stats['total'] }}</p>
                        </div>
                        <div
                            class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full text-indigo-600 dark:text-indigo-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500 transition-all hover:scale-105">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">In Progress</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                                {{ $stats['pending'] }}</p>
                        </div>
                        <div
                            class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full text-yellow-600 dark:text-yellow-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border-l-4 border-green-500 transition-all hover:scale-105">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                                {{ $stats['completed'] }}</p>
                        </div>
                        <div
                            class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600 dark:text-green-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 
                BAGIAN 2: TABEL RIWAYAT SEMUA LAPORAN
                Menggunakan Pagination 10 per halaman.
            --}}
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                    Riwayat Laporan (Recently)
                </h2>
                <a href="{{ route('login') }}"
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                    + Lapor Kerusakan
                </a>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if (isset($workOrders) && count($workOrders) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400 transition-colors">
                            <thead
                                class="bg-gray-100 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-500 transition-colors">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Nomor Tiket</th>
                                    <th scope="col" class="px-6 py-4">Judul Masalah</th>
                                    <th scope="col" class="px-6 py-4">Pelapor</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                    <th scope="col" class="px-6 py-4">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($workOrders as $wo)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td
                                            class="px-6 py-4 font-medium text-indigo-600 dark:text-indigo-400 font-mono">
                                            {{ $wo->ticket_num }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                                            {{ $wo->kerusakan }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $wo->requester->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusConfig = match ($wo->work_status) {
                                                    'pending' => [
                                                        'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                                        'text' => 'text-yellow-700 dark:text-yellow-400',
                                                        'dot' => 'bg-yellow-500',
                                                    ],
                                                    'in_progress' => [
                                                        'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                                                        'text' => 'text-blue-700 dark:text-blue-400',
                                                        'dot' => 'bg-blue-500',
                                                    ],
                                                    'completed' => [
                                                        'bg' => 'bg-green-100 dark:bg-green-900/30',
                                                        'text' => 'text-green-700 dark:text-green-400',
                                                        'dot' => 'bg-green-500',
                                                    ],
                                                    default => [
                                                        'bg' => 'bg-gray-100 dark:bg-gray-700',
                                                        'text' => 'text-gray-700 dark:text-gray-400',
                                                        'dot' => 'bg-gray-500',
                                                    ],
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                                {{ ucfirst(str_replace('_', ' ', $wo->work_status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $wo->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 
                        PAGINATION LINKS
                        Ini akan menampilkan tombol navigasi (Previous 1 2 3 Next)
                        Tampilannya akan otomatis gelap mengikuti tema jika file tailwind.config.js sudah benar.
                    --}}
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $workOrders->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-full mb-4">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada data</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Laporan kerusakan akan muncul di sini.</p>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <footer
        class="bg-white dark:bg-gray-900 py-6 text-center text-sm text-gray-500 dark:text-gray-600 border-t border-gray-200 dark:border-gray-800 transition-colors duration-300">
        &copy; {{ date('Y') }} IT Department. All rights reserved.
    </footer>

</body>

</html>
