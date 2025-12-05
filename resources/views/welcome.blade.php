<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Engineering Order</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">

    {{-- Memuat script default Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS TAMBAHAN MANUAL (Agar animasi jalan tanpa edit config tailwind) --}}
    <style>
        @keyframes scroll {
            0% {
                /* Mulai dari luar sisi kanan (100% dari lebar elemen/parent) */
                transform: translateX(100%);
            }

            100% {
                /* Berakhir di luar sisi kiri */
                transform: translateX(-100%);
            }
        }

        .animate-scroll {
            display: flex;
            /* Wajib flex agar menyamping */
            animation: scroll 20s linear infinite;
            /* Durasi diperlambat (15s) agar teks terbaca */
            width: max-content;
            /* Agar lebar mengikuti konten */
        }

        .animate-scroll:hover {
            animation-play-state: paused;
        }

        .mask-image-gradient {
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }

        /* Mencegah scroll horizontal pada halaman */
        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    {{-- WRAPPER UTAMA: Mengatur Layout Footer Sticky --}}
    <div class="min-h-screen flex flex-col">

        {{-- NAVBAR --}}
        <nav x-data="{ mobileMenuOpen: false, profileOpen: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="relative flex h-16 items-center justify-between">
                    {{-- Mobile Menu Button --}}
                    <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 focus:outline-none">
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
                            {{-- Ganti src logo jika perlu --}}
                            <img src="{{ asset('assets/logo.png') }}" alt="Logo Perusahaan" class="h-10 w-auto">
                        </div>
                        <div class="hidden sm:ml-6 sm:block">
                            <div class="flex space-x-4">
                                <a href="#"
                                    class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-900">Home</a>
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900">Dashboard</a>
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
                                    class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2">
                                    <img class="h-8 w-8 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=6366f1&color=fff"
                                        alt="">
                                </button>
                                <div x-show="profileOpen" @click.away="profileOpen = false"
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    style="display: none;">
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
                                    class="rounded-md border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- KONTEN UTAMA --}}
        <main class="flex-grow p-6">
            <div class="w-full max-w-7xl mx-auto">

                {{-- JUDUL --}}
                <div class="text-center mb-10 mt-4">
                    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                        Engineering Improvement <span class="text-indigo-600">Order</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-600">Live report status tracking system.</p>
                </div>

                {{-- MOVING CARD / MARQUEE --}}
                @if (isset($liveReports) && count($liveReports) > 0)
                    <div class="mb-10 w-full overflow-hidden">
                        <h3
                            class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2 px-1 flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                            Live Updates
                        </h3>

                        <div class="relative w-full overflow-hidden mask-image-gradient">
                            {{-- Container Animasi --}}
                            <div class="animate-scroll gap-4 py-2">

                                {{-- LOOP 1: DATA ASLI --}}
                                @foreach ($liveReports as $report)
                                    @php
                                        // LOGIKA WARNA (Sama seperti tabel)
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                        ];

                                        // Sesuaikan '$report->status' dengan nama kolom di database Anda
                                        $statusCheck = strtolower($report->status ?? $report->work_status);
                                        $currentClass =
                                            $statusClasses[$statusCheck] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp

                                    <div
                                        class="w-72 flex-shrink-0 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <span
                                                class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $report->ticket_num }}</span>
                                            <span
                                                class="text-[10px] text-gray-400">{{ $report->created_at->diffForHumans() }}</span>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-800 line-clamp-1">
                                            {{ $report->kerusakan }}
                                        </h4>
                                        <div class="mt-2 flex justify-between items-center">
                                            <span
                                                class="text-xs text-gray-500">{{ Str::limit($report->requester->name ?? 'User', 10) }}</span>

                                            {{-- SPAN STATUS BERWARNA --}}
                                            <span
                                                class="text-[10px] px-2 py-0.5 rounded-full border {{ $currentClass }}">
                                                {{ str_replace('_', ' ', $report->work_status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- LOOP 2: DATA DUPLIKAT (Agar animasi seamless) --}}
                                @foreach ($liveReports as $report)
                                    @php
                                        // LOGIKA WARNA (Harus diulang di loop kedua juga)
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                        ];

                                        $statusCheck = strtolower($report->status ?? $report->work_status);
                                        $currentClass =
                                            $statusClasses[$statusCheck] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp

                                    <div
                                        class="w-72 flex-shrink-0 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <span
                                                class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $report->ticket_num }}</span>
                                            <span
                                                class="text-[10px] text-gray-400">{{ $report->created_at->diffForHumans() }}</span>
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-800 line-clamp-1">
                                            {{ $report->kerusakan }}
                                        </h4>
                                        <div class="mt-2 flex justify-between items-center">
                                            <span
                                                class="text-xs text-gray-500">{{ Str::limit($report->requester->name ?? 'User', 10) }}</span>

                                            {{-- SPAN STATUS BERWARNA --}}
                                            <span
                                                class="text-[10px] px-2 py-0.5 rounded-full border {{ $currentClass }}">
                                                {{ str_replace('_', ' ', $report->work_status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif

                {{-- STATISTIK CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-indigo-500">
                        <p class="text-sm font-medium text-gray-500">Total Ticket</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500">
                        <p class="text-sm font-medium text-gray-500">In Progress</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['completed'] ?? 0 }}</p>
                    </div>
                </div>

                {{-- TABEL --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-10">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-800">Riwayat Laporan</h2>

                    </div>
                    @if (isset($workOrders) && count($workOrders) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                    <tr>
                                        <th class="px-6 py-4">Tiket</th>
                                        <th class="px-6 py-4">Kerusakan</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($workOrders as $wo)
                                        @php
                                            // 1. LOGIKA WARNA
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'completed' => 'bg-green-100 text-green-800 border-green-200',
                                                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                            ];

                                            // Cek status, ubah ke lowercase, default ke abu-abu
                                            // Pastikan Anda menggunakan kolom yang benar ($wo->status atau $wo->work_status) untuk pengecekan logic
                                            $statusCheck = strtolower($wo->work_status);
                                            $currentClass =
                                                $statusClasses[$statusCheck] ??
                                                'bg-gray-100 text-gray-800 border-gray-200';
                                        @endphp

                                        {{-- 2. BARIS TABEL (TR) --}}
                                        <tr>
                                            <td class="px-6 py-4 font-mono text-indigo-600">{{ $wo->ticket_num }}</td>
                                            <td class="px-6 py-4">{{ $wo->kerusakan }}</td>

                                            {{-- 3. KOLOM STATUS (TD) - Masukkan SPAN di sini --}}
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-semibold border {{ $currentClass }}">
                                                    {{-- Tampilkan teks status --}}
                                                    {{ $wo->work_status }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4">{{ $wo->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 bg-gray-50">
                            {{ $workOrders->links() }}
                        </div>
                    @else
                        <div class="p-10 text-center text-gray-500">Belum ada data tersedia.</div>
                    @endif
                </div>

            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="bg-white py-6 text-center text-sm text-gray-500 border-t border-gray-200 mt-auto">
            &copy; {{ date('Y') }} Fairuz Bachri. All rights reserved.
        </footer>
    </div>

</body>

</html>
