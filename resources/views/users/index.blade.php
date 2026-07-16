<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen User</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola akun tim CS dan admin</p>
            </div>
            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium">Nama</th>
                        <th class="text-left px-6 py-3 font-medium">Email</th>
                        <th class="text-left px-6 py-3 font-medium">Role</th>
                        <th class="text-right px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold text-xs text-white shrink-0
                                    {{ $user->role === 'admin' ? 'bg-gradient-to-br from-rose-500 to-rose-600' : 'bg-gradient-to-br from-blue-500 to-blue-700' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-slate-900">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                <span class="text-xs text-slate-400">(Anda)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-6 py-3">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700">
                                    <i class="fas fa-user-shield text-[10px]"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    <i class="fas fa-headset text-[10px]"></i> CS
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('users.edit', $user) }}" class="text-slate-400 hover:text-emerald-600 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                            <i class="fas fa-user-slash text-3xl mb-3 block"></i>
                            Belum ada user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>