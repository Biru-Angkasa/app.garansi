<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Konfirmasi Password</h1>
        <p class="text-sm text-slate-500 mt-1">Ini adalah area aman. Mohon konfirmasi password Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                <x-text-input id="password" class="block w-full pl-10" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            <i class="fas fa-check text-xs"></i> {{ __('Konfirmasi') }}
        </x-primary-button>
    </form>
</x-guest-layout>
