<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
        <p class="text-sm text-slate-500 mt-1">Masuk untuk mengelola data garansi</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative">
                <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                <x-text-input id="password" class="block w-full pl-10 pr-10" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full">
            <i class="fas fa-right-to-bracket text-xs"></i> {{ __('Masuk') }}
        </x-primary-button>

        <!-- Link ke Register -->
        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-500">
                {{ __('Belum punya akun?') }}
                <a class="font-medium text-blue-600 hover:text-blue-700 transition-colors" href="{{ route('register') }}">
                    {{ __('Daftar di sini') }}
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>