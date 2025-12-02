<nav x-data="{ mobileMenuOpen: false, profileOpen: false }"
    class="bg-white border-b border-gray-200 sticky top-0 z-50 transition-colors duration-300 shadow-sm">
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
                        <a href="/"
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

                {{-- TOMBOL DARK MODE DIHAPUS DI SINI --}}

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
<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="login" :value="__('Email / Username')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
