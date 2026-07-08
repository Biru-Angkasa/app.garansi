<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Profil Saya</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-800 text-sm font-medium flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-2xl border border-rose-200 p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
