<div x-data="{ loading: false }" x-show="loading" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @pageshow.window="loading = false" @beforeunload.window="loading = true"
    @submit-form.window="loading = true"
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/80 dark:bg-gray-900/90 backdrop-blur-sm"
    style="display: none;">

    {{-- Animasi Gear Engineering --}}
    <div class="relative">
        {{-- Gear Besar --}}
        <svg class="w-24 h-24 text-blue-600 animate-[spin_3s_linear_infinite]" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v2h-2zm0 10h2v2h-2z" />
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v2m0 8v2M6 12H4m16 0h-2m-2.17-5.17l-1.42 1.42M8.59 15.41l-1.42 1.42m11.66 0l-1.42-1.42M8.59 8.59L7.17 7.17" />
        </svg>

        {{-- Gear Kecil (Berputar berlawanan) --}}
        <div class="absolute -bottom-4 -right-4">
            <svg class="w-12 h-12 text-indigo-500 animate-[spin_2s_linear_infinite_reverse]" fill="none"
                viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4v1m0 14v1m8-8h-1M5 12H4m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707.707" />
                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2" />
            </svg>
        </div>
    </div>

    <div class="mt-8 text-center">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white animate-pulse">Please wait..</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Engineer Improvement Order</p>
    </div>
</div>

{{-- Script untuk memicu loading saat form disubmit --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tangkap semua form di halaman
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                // Pemicu event AlpineJS agar loading muncul
                window.dispatchEvent(new CustomEvent('submit-form'));
            });
        });
    });
</script>
