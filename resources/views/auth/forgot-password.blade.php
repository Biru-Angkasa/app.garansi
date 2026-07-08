<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Lupa Password?</h1>
        <p class="text-sm text-slate-500 mt-1">Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative">
                <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            <i class="fas fa-paper-plane text-xs"></i> {{ __('Kirim Tautan Reset Password') }}
        </x-primary-button>

        <p class="text-center text-sm text-slate-500">
            <a class="font-medium text-blue-600 hover:text-blue-700 transition-colors" href="{{ route('login') }}">
                <i class="fas fa-arrow-left text-xs mr-1"></i> {{ __('Kembali ke halaman masuk') }}
            </a>
        </p>
    </form>
</x-guest-layout>
