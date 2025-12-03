<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <title>engIO</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="icon" href="{{ asset('favicon.ico') }}"> --}}
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col">
    <x-loading-screen />

    {{-- NAVBAR --}}
    <nav x-data="{ mobileMenuOpen: false, profileOpen: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">

                {{-- Mobile Menu --}}
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
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
                                class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-900 transition-colors">Home</a>
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors">Dashboard</a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Right Buttons --}}
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 gap-3">

                    @auth
                        <div class="relative ml-3">
                            <button @click="profileOpen = !profileOpen" type="button"
                                class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=6366f1&color=fff"
                                    alt="">
                            </button>
                            <div x-show="profileOpen" @click.away="profileOpen = false"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                style="display: none;">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
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
                                class="rounded-md border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">Register</a>
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
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    Engineering Improvement <span class="text-indigo-600">Order</span>
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    Live report. Track the status of your engineering improvement orders in real-time.
                </p>
            </div>

            {{-- BAGIAN 1: STATISTIK CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-indigo-500 transition-all hover:scale-105">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Ticket</p>
                            <p class="text-3xl font-bold text-indigo-600 mt-1">
                                {{ $stats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500 transition-all hover:scale-105">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">In Progress</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-1">
                                {{ $stats['pending'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500 transition-all hover:scale-105">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Completed</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">
                                {{ $stats['completed'] }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: TABEL RIWAYAT --}}
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                    Riwayat Laporan (Recently)
                </h2>
                <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:underline">
                    + Lapor Kerusakan
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                @if (isset($workOrders) && count($workOrders) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500">
                            <thead class="bg-gray-100 text-xs uppercase text-gray-500">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Nomor Tiket</th>
                                    <th scope="col" class="px-6 py-4">Judul Masalah</th>
                                    <th scope="col" class="px-6 py-4">Pelapor</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                    <th scope="col" class="px-6 py-4">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($workOrders as $wo)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-indigo-600 font-mono">
                                            {{ $wo->ticket_num }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-900">
                                            {{ $wo->kerusakan }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $wo->requester->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusConfig = match ($wo->work_status) {
                                                    'pending' => [
                                                        'bg' => 'bg-yellow-100',
                                                        'text' => 'text-yellow-700',
                                                        'dot' => 'bg-yellow-500',
                                                    ],
                                                    'in_progress' => [
                                                        'bg' => 'bg-blue-100',
                                                        'text' => 'text-blue-700',
                                                        'dot' => 'bg-blue-500',
                                                    ],
                                                    'completed' => [
                                                        'bg' => 'bg-green-100',
                                                        'text' => 'text-green-700',
                                                        'dot' => 'bg-green-500',
                                                    ],
                                                    default => [
                                                        'bg' => 'bg-gray-100',
                                                        'text' => 'text-gray-700',
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

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $workOrders->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="bg-gray-100 p-4 rounded-full mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada data</h3>
                        <p class="text-gray-500 mt-1">Laporan kerusakan akan muncul di sini.</p>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <footer class="bg-white py-6 text-center text-sm text-gray-500 border-t border-gray-200">
        &copy; {{ date('Y') }} Fairuz Bachri. All rights reserved.
    </footer>

</body>

</html>
