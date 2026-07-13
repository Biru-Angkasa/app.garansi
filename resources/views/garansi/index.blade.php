@extends('layouts.app')
@section('title', 'Data Garansi')

@section('content')
<div class="relative space-y-8 pb-12">
    <div class="absolute inset-x-0 top-0 -z-10 h-80 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.08),_transparent_50%),radial-gradient(circle_at_top_right,_rgba(15,23,42,0.05),_transparent_45%)]"></div>
        {{-- Header --}}
        <header class="rounded-2xl border border-slate-200/40 bg-white/80 p-8 shadow-[0_16px_40px_rgba(15,23,42,0.04)] backdrop-blur-md sm:p-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 rounded-lg border border-slate-200/60 bg-slate-50/80 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Manajemen Garansi
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold tracking-tight text-slate-950" style="line-height: 1.2;">Data Garansi</h1>
                        <p class="text-sm leading-7 text-slate-600 max-w-xl">Kelola dan pantau seluruh klaim garansi dengan mudah. Gunakan filter dan pencarian untuk mempercepat tindak lanjut.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('garansi.create') }}"
                       class="group inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition-all duration-200 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/35 active:scale-95">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Tambah Data</span>
                    </a>
                </div>
            </div>

            {{-- Keterangan Warna --}}
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center gap-6 text-xs text-slate-600">
                <div class="flex items-center gap-2.5">
                    <div class="flex-shrink-0 h-2 w-2 rounded-full bg-amber-500"></div>
                    <span class="font-medium">Diam 1 hari, belum selesai</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="flex-shrink-0 h-2 w-2 rounded-full bg-rose-500"></div>
                    <span class="font-medium">Diam ≥ 2 hari, belum selesai</span>
                </div>
            </div>
        </header>

        {{-- Filter --}}
        <div class="rounded-2xl border border-slate-200/40 bg-white shadow-sm">
            <div class="border-b border-slate-100/50 px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold tracking-tight text-slate-950">Cari dan Filter</h2>
                        <p class="mt-1.5 text-sm text-slate-600">Kombinasikan pencarian dan status untuk menyaring data dengan cepat.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-lg bg-slate-50/80 px-3 py-1.5 text-xs font-medium text-slate-600 border border-slate-200/60">
                        <i class="fas fa-zap text-slate-400 text-[10px]"></i>
                        <span>Filter cepat</span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form method="GET" class="flex flex-col lg:flex-row gap-4 lg:items-end lg:justify-between">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-3">Cari Data</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, invoice, no HP, atau nomor seri..."
                                   class="w-full rounded-xl border border-slate-200/60 bg-white px-12 py-3 text-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-blue-50/30">
                        </div>
                    </div>

                    <div class="w-full lg:w-[200px]">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-3">Status</label>
                        <select name="status"
                                class="w-full rounded-xl border border-slate-200/60 bg-white px-4 py-3 text-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-blue-50/30">
                            <option value="">Semua Status</option>
                            @foreach($statusList as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 active:scale-95">
                            <i class="fas fa-filter text-xs"></i>
                            <span>Terapkan</span>
                        </button>
                        <a href="{{ route('garansi.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200/60 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50 hover:border-slate-300">
                            Ulang
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl border border-slate-200/40 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100/50 px-8 py-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold tracking-tight text-slate-950">Daftar Garansi</h2>
                        <p class="mt-1.5 text-sm text-slate-600">Tampilan data garansi berdasarkan pencarian dan filter Anda.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2.5 text-xs font-medium text-slate-600 bg-slate-50/80 px-3 py-1.5 rounded-lg border border-slate-200/60">
                        <i class="fas fa-list text-slate-400 text-[10px]"></i>
                        <span>{{ $garansis->total() }} data</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/60 text-[11px] uppercase tracking-wider text-slate-600 font-semibold border-b border-slate-100/50">
                        <tr>
                            <th class="px-8 py-4">Nama</th>
                            <th class="px-8 py-4">Invoice</th>
                            <th class="px-8 py-4">No HP</th>
                            <th class="px-8 py-4">Barang</th>
                            <th class="px-8 py-4">Lokasi Chat</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4">Tgl Sampai</th>
                            <th class="px-8 py-4">Terakhir Update</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/50">
                        @forelse($garansis as $garansi)
                            @php
                                $idleDays = $garansi->updated_at
                                    ->copy()
                                    ->startOfDay()
                                    ->diffInDays(now()->copy()->startOfDay());

                                $belumSelesai = strtolower(trim($garansi->status)) !== 'selesai';

                                $borderClass = '';
                                $idleColor = 'text-slate-400';

                                if ($belumSelesai) {
                                    if ($idleDays >= 2) {
                                        $borderClass = 'border-l-4 border-rose-500 bg-rose-50/30';
                                        $idleColor = 'text-rose-600 font-semibold';
                                    } elseif ($idleDays >= 1) {
                                        $borderClass = 'border-l-4 border-amber-500 bg-amber-50/30';
                                        $idleColor = 'text-amber-600 font-semibold';
                                    }
                                }
                            @endphp

                            <tr class="group hover:bg-slate-50/60 transition-colors duration-150">
                                <td class="px-8 py-4 font-semibold text-slate-950 {{ $borderClass }}">
                                    <div class="max-w-[200px] truncate">{{ $garansi->nama }}</div>
                                </td>

                                <td class="px-8 py-4 font-mono text-xs text-slate-500">
                                    {{ $garansi->invoice_pembelian ?? '-' }}
                                </td>

                                <td class="px-8 py-4 text-slate-700 text-sm">
                                    {{ $garansi->no_hp }}
                                </td>

                                <td class="px-8 py-4 text-slate-700">
                                    @if($garansi->items->count() > 1)
                                        <div class="max-w-[240px] truncate">
                                            <span class="text-xs">{{ $garansi->items->first()->nama_barang }}</span>
                                            <span class="text-xs text-slate-400">+{{ $garansi->items->count() - 1 }}</span>
                                        </div>
                                    @else
                                        <div class="text-xs">{{ $garansi->items->first()->nama_barang ?? '-' }}</div>
                                    @endif
                                </td>

                                <td class="px-8 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100/60 px-2.5 py-1.5 text-xs font-medium text-slate-700 border border-slate-200/60">
                                        {{ ucfirst($garansi->lokasi_chat) }}
                                    </span>
                                </td>

                                <td class="px-8 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1.5 text-xs font-bold {{ $garansi->status_color }}">
                                        {{ ucfirst($garansi->status) }}
                                    </span>
                                </td>

                                <td class="px-8 py-4 text-slate-700 text-xs font-medium">
                                    {{ $garansi->tanggal_sampai->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-8 py-4">
                                    <div class="text-xs text-slate-700 font-medium">{{ $garansi->updated_at->format('d/m/Y H:i') }}</div>
                                    <div class="mt-1.5 text-[10px] {{ $idleColor }}">{{ $idleDays }} hari lalu</div>
                                </td>

                                <td class="px-8 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('garansi.show', $garansi) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200/60 bg-white text-slate-500 hover:text-blue-600 hover:bg-blue-50/30 hover:border-blue-200/60 transition-all duration-200"
                                           title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        <a href="{{ route('garansi.edit', $garansi) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200/60 bg-white text-slate-500 hover:text-emerald-600 hover:bg-emerald-50/30 hover:border-emerald-200/60 transition-all duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('garansi.destroy', $garansi) }}" method="POST" class="inline"
                                                  onsubmit="event.preventDefault(); confirmDelete(this, 'Data garansi {{ addslashes($garansi->nama) }} akan dihapus permanen.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200/60 bg-white text-slate-500 hover:text-rose-600 hover:bg-rose-50/30 hover:border-rose-200/60 transition-all duration-200"
                                                        title="Hapus">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-8 py-20 text-center">
                                    <div class="mx-auto max-w-md space-y-4">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100/60 text-slate-300">
                                            <i class="fas fa-inbox text-3xl"></i>
                                        </div>
                                        <div class="space-y-1.5">
                                            <h3 class="text-sm font-semibold text-slate-950">Tidak ada data ditemukan</h3>
                                            <p class="text-sm text-slate-600">Coba ubah kata kunci pencarian atau sesuaikan filter status.</p>
                                        </div>
                                        <a href="{{ route('garansi.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 transition-all duration-200">
                                            <i class="fas fa-rotate-right text-xs"></i>
                                            <span>Ulang Filter</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-6 border-t border-slate-100/50">
                {{ $garansis->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection