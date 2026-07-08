<section>
    <header class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
            <i class="fas fa-key text-sm"></i>
        </div>
        <div>
            <h2 class="font-semibold text-slate-900">
                {{ __('Ubah Password') }}
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                {{ __('Gunakan password yang panjang dan acak agar akun Anda tetap aman.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full md:w-2/3" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="update_password_password" :value="__('Password Baru')" />
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button><i class="fas fa-save text-xs"></i> {{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600 font-medium flex items-center gap-1.5"
                ><i class="fas fa-check-circle"></i> {{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
