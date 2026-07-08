<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope-circle-check text-2xl"></i>
        </div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Email</h1>
        <p class="text-sm text-slate-500 mt-2">Terima kasih sudah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirim. Jika Anda tidak menerima email tersebut, kami akan mengirimkan yang baru.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2.5">
            <i class="fas fa-check-circle text-emerald-500"></i>
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full">
                <i class="fas fa-paper-plane text-xs"></i> {{ __('Kirim Ulang Email Verifikasi') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i class="fas fa-sign-out-alt text-xs mr-1"></i> {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
