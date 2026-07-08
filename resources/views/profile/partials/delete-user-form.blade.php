<section class="space-y-6">
    <header class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
            <i class="fas fa-triangle-exclamation text-sm"></i>
        </div>
        <div>
            <h2 class="font-semibold text-slate-900">
                {{ __('Hapus Akun') }}
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                {{ __('Setelah akun dihapus, seluruh data dan sumber dayanya akan dihapus secara permanen. Unduh data yang ingin Anda simpan sebelum menghapus akun.') }}
            </p>
        </div>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    ><i class="fas fa-trash text-xs"></i> {{ __('Hapus Akun') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">
                {{ __('Yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ __('Setelah akun dihapus, seluruh data dan sumber dayanya akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi penghapusan akun.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button>
                    <i class="fas fa-trash text-xs"></i> {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
