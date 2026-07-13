<x-guest-layout>
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-blue-700">
            <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
            Akses staf
        </div>

        <div class="mt-5 space-y-3">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                Selamat datang kembali
            </h1>
            <p class="max-w-md text-sm leading-6 text-slate-500 sm:text-[15px]">
                Masuk untuk mengelola data garansi, melihat riwayat klaim, dan memantau pembaruan pelanggan dengan lebih rapi.
            </p>
        </div>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email')" class="text-[11px] uppercase tracking-[0.22em] text-slate-500" />
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <x-text-input
                    id="email"
                    class="block w-full pl-11 pr-4 py-3.5 bg-white/90"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@email.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-1.5" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="text-[11px] uppercase tracking-[0.22em] text-slate-500" />
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <x-text-input
                    id="password"
                    class="block w-full pl-11 pr-12 py-3.5 bg-white/90"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center rounded-lg px-2 text-slate-400 transition hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                    tabindex="-1"
                    aria-label="Toggle password visibility"
                >
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-slate-600">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 shadow-sm transition focus:ring-blue-500/40"
                    name="remember"
                >
                <span>{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a
                    class="text-sm font-medium text-blue-600 transition hover:text-blue-700"
                    href="{{ route('password.request') }}"
                >
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center rounded-2xl py-3.5 text-[15px] font-semibold shadow-lg shadow-blue-600/20">
            <i class="fas fa-right-to-bracket text-xs transition-transform group-hover:translate-x-0.5"></i>
            {{ __('Masuk') }}
        </x-primary-button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-500">
                {{ __('Belum punya akun?') }}
                <a class="font-semibold text-blue-600 transition hover:text-blue-700" href="{{ route('register') }}">
                    {{ __('Daftar di sini') }}
                </a>
            </p>
        @endif
    </form>

    @if (Route::has('tracking.index'))
        <div class="mt-8">
            <div class="mb-3 flex items-center gap-2">
                <span class="h-px flex-1 bg-slate-200"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">atau</span>
                <span class="h-px flex-1 bg-slate-200"></span>
            </div>

            <a
                href="{{ route('tracking.index') }}"
                class="group flex items-center justify-between rounded-3xl border border-blue-100 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md"
            >
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/20 transition-transform duration-300 group-hover:scale-105">
                        <i class="fas fa-box-open text-base"></i>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-900">
                            Cek status garansi
                        </h3>
                        <p class="text-sm leading-5 text-slate-500">
                            Lacak status tanpa perlu login.
                        </p>
                    </div>
                </div>

                <span class="text-blue-600 transition-transform duration-300 group-hover:translate-x-1">
                    <i class="fas fa-arrow-right"></i>
                </span>
            </a>
        </div>
    @endif
</x-guest-layout>
