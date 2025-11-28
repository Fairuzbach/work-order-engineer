<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- 
        STATE MANAGEMENT (Alpine.js):
        - showDetailModal: Untuk melihat detail tiket
        - showCreateModal: Untuk form buat tiket baru (INI YANG BARU)
        - ticket: Data tiket yang sedang dilihat
    --}}
    <div class="py-12" x-data="{ showDetailModal: false, showCreateModal: false, ticket: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ALERT SUKSES (Muncul jika ada pesan dari controller) --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Bagian Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-900 font-bold text-lg">Total Tiket</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $workOrders->total() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-900 font-bold text-lg">Perlu Dikerjakan</div>
                    <div class="text-3xl font-bold text-yellow-600">
                        {{ \App\Models\WorkOrder::where('work_status', 'pending')->count() }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-900 font-bold text-lg">Selesai</div>
                    <div class="text-3xl font-bold text-green-600">
                        {{ \App\Models\WorkOrder::where('work_status', 'completed')->count() }}
                    </div>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Laporan Masuk</h3>

                        {{-- TOMBOL BUAT TIKET (Memicu Modal Create) --}}
                        <button @click="showCreateModal = true"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Laporan Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tiket</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kerusakan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelapor</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prioritas</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($workOrders as $wo)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 font-mono">
                                            {{ $wo->ticket_num }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $wo->kerusakan }}</div>
                                            <div class="text-xs text-gray-500 truncate w-48">{{ $wo->kerusakan_detail }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $wo->requester->name ?? '-' }}
                                            <div class="text-xs text-gray-400">{{ $wo->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColor = match ($wo->work_status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $wo->work_status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="text-sm font-semibold uppercase {{ $wo->priority === 'high' || $wo->priority === 'critical' ? 'text-red-600' : 'text-gray-600' }}">
                                                {{ ucfirst($wo->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Tombol Detail (Memicu Modal Detail) --}}
                                            <button
                                                @click="ticket = {{ $wo->toJson() }}; ticket.requester_name = '{{ $wo->requester->name ?? '-' }}'; showDetailModal = true"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Detail
                                            </button>

                                            @if (auth()->user()->role === 'admin')
                                                <a href="#"
                                                    class="text-gray-600 hover:text-gray-900 font-bold">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada laporan masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $workOrders->links() }}</div>
                </div>
            </div>
        </div>

        {{-- 
            =============================================
            MODAL 1: CREATE TICKET (FORMULIR LAPORAN)
            =============================================
        --}}
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showCreateModal" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCreateModal = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    {{-- Header Modal Create --}}
                    <div class="bg-blue-600 px-4 py-3 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Buat Laporan Kerusakan
                        </h3>
                        <button @click="showCreateModal = false" class="text-blue-100 hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Input --}}
                    <form action="{{ route('work-orders.store') }}" method="POST">
                        @csrf
                        <div class="px-4 py-5 sm:p-6 space-y-4">

                            {{-- Input 1: Judul Kerusakan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kerusakan</label>
                                <input type="text" name="kerusakan" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Contoh: AC Bocor di Ruang Meeting">
                            </div>

                            {{-- Input 2: Detail Kerusakan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Detail Kerusakan</label>
                                <textarea name="kerusakan_detail" rows="3" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Jelaskan detail masalahnya secara rinci..."></textarea>
                            </div>

                            {{-- Input 3: Priority --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Prioritas</label>
                                <select name="priority"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="low">Low (Santai)</option>
                                    <option value="medium" selected>Medium (Biasa)</option>
                                    <option value="high">High (Penting)</option>
                                    <option value="critical">Critical (Darurat!)</option>
                                </select>
                            </div>

                            {{-- Info Pelapor (Read Only) --}}
                            <div class="bg-gray-50 p-3 rounded text-sm text-gray-500 flex justify-between">
                                <span>Pelapor: <strong>{{ auth()->user()->name }}</strong></span>
                                <span>Status Awal: <strong>Pending</strong></span>
                            </div>

                        </div>

                        {{-- Footer Form --}}
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Kirim Laporan
                            </button>
                            <button type="button" @click="showCreateModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 
            =============================================
            MODAL 2: DETAIL TICKET (Read Only)
            =============================================
        --}}
        <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showDetailModal" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDetailModal = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showDetailModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    {{-- Header Modal Detail --}}
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Detail Work Order</h3>
                        <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Isi Modal Detail --}}
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4" x-if="ticket">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Nomor Tiket</span>
                                    <p class="text-xl font-bold text-blue-600 font-mono" x-text="ticket.ticket_num">
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"
                                    x-text="ticket.work_status.charAt(0).toUpperCase() + ticket.work_status.slice(1).replace('_', ' ')"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Judul Masalah</span>
                                <h4 class="text-lg font-medium text-gray-900" x-text="ticket.kerusakan"></h4>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Deskripsi Detail</span>
                                <p class="text-sm text-gray-600 mt-1 whitespace-pre-wrap"
                                    x-text="ticket.kerusakan_detail"></p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Prioritas</span>
                                    <p class="text-sm font-medium uppercase" x-text="ticket.priority"></p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Pelapor</span>
                                    <p class="text-sm font-medium text-gray-900" x-text="ticket.requester_name"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button"
                            class="inline-flex w-full justify-center rounded-md bg-white border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 sm:ml-3 sm:w-auto"
                            @click="showDetailModal = false">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
