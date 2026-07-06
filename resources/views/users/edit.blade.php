<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit User</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $user->name }}</p>
            </div>
            <a href="{{ route('users.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-medium flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                @error('name') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                @error('email') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                @error('password') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Role <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="cs" {{ old('role', $user->role) === 'cs' ? 'checked' : '' }} class="peer sr-only" required>
                        <div class="border-2 border-slate-200 rounded-xl p-3.5 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-colors">
                            <i class="fas fa-headset text-xl text-blue-500 mb-1.5"></i>
                            <div class="text-sm font-medium text-slate-900">Customer Service</div>
                            <div class="text-xs text-slate-400">Akses data garansi</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="admin" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }} class="peer sr-only" required>
                        <div class="border-2 border-slate-200 rounded-xl p-3.5 text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-colors">
                            <i class="fas fa-user-shield text-xl text-rose-500 mb-1.5"></i>
                            <div class="text-sm font-medium text-slate-900">Admin</div>
                            <div class="text-xs text-slate-400">Akses penuh sistem</div>
                        </div>
                    </label>
                </div>
                @error('role') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>