@extends('layouts.app')
@section('title', 'Data Garansi')

@section('content')
<div class="relative space-y-6 pb-12">
    <div class="absolute inset-x-0 top-0 -z-10 h-80 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.08),_transparent_50%),radial-gradient(circle_at_top_right,_rgba(15,23,42,0.05),_transparent_45%)]"></div>
    
    {{-- Header --}}
    <header class="rounded-2xl border border-slate-200/40 bg-white/80 p-6 shadow-[0_16px_40px_rgba(15,23,42,0.04)] backdrop-blur-md sm:p-7">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 rounded-lg border border-slate-200/60 bg-slate-50/80 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                    Manajemen Garansi
                </div>
                <div class="space-y-1.5">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950" style="line-height: 1.2;">Data Garansi</h1>
                    <p class="text-sm leading-6 text-slate-600 max-w-xl">Kelola dan pantau seluruh klaim garansi dengan mudah. Gunakan filter dan pencarian untuk mempercepat tindak lanjut.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('garansi.create') }}"
                   class="group inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition-all duration-200 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/35 active:scale-95">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Tambah Data</span>
                </a>
            </div>
        </div>

        {{-- Keterangan Warna --}}
        <div class="mt-6 pt-5 border-t border-slate-100 flex flex-wrap items-center gap-6 text-xs text-slate-600">
            <div class="flex items-center gap-2.5">
                <div class="flex-shrink-0 h-2 w-2 rounded-full bg-amber-500"></div>
                <span class="font-medium">Diam 1-2 hari</span>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="flex-shrink-0 h-2 w-2 rounded-full bg-rose-500"></div>
                <span class="font-medium">Diam ≥ 2 hari (SLA Breach)</span>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="flex-shrink-0 h-2 w-2 rounded-full bg-slate-300"></div>
                <span class="font-medium">Selesai</span>
            </div>
        </div>
    </header>

    {{-- Filter --}}
    <div class="rounded-2xl border border-slate-200/40 bg-white shadow-sm">
        <div class="border-b border-slate-100/50 px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold tracking-tight text-slate-950">Cari dan Filter</h2>
                    <p class="mt-1 text-sm text-slate-600">Temukan data dengan cepat menggunakan pencarian dan filter status.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-lg bg-slate-50/80 px-3 py-1.5 text-xs font-medium text-slate-600 border border-slate-200/60">
                    <i class="fas fa-zap text-slate-400 text-[10px]"></i>
                    <span>Filter cepat</span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Search Field --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-3">Pencarian</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama, invoice, HP, atau SN..."
                                   class="w-full rounded-xl border border-slate-200/60 bg-white px-11 py-2.5 text-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-blue-50/30">
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-3">Status</label>
                        <select name="status"
                                class="w-full rounded-xl border border-slate-200/60 bg-white px-4 py-2.5 text-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-blue-50/30">
                            <option value="">Semua Status</option>
                            @foreach($statusList as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 items-end">
                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 active:scale-95">
                            <i class="fas fa-filter text-xs"></i>
                            <span class="hidden sm:inline">Terapkan</span>
                            <span class="sm:hidden">Filter</span>
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('garansi.index') }}"
                               class="inline-flex items-center justify-center h-11 w-11 rounded-xl border border-slate-200/60 bg-white text-slate-700 transition-all duration-200 hover:bg-slate-50 hover:border-slate-300"
                               title="Reset filter">
                                <i class="fas fa-rotate-right text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Query Info --}}
                @if(request('search') || request('status'))
                    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center gap-3 text-xs text-slate-600">
                        <span class="font-medium text-slate-900">Filter aktif:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg font-medium border border-blue-200/60">
                                <i class="fas fa-search text-[10px]"></i>
                                "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg font-medium border border-slate-200/60">
                                <i class="fas fa-tag text-[10px]"></i>
                                {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200/40 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-100/50 px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold tracking-tight text-slate-950">Daftar Garansi</h2>
                <p class="mt-1 text-sm text-slate-600">{{ $garansis->total() }} total data</p>
            </div>
            <div class="hidden sm:flex items-center gap-2.5 text-xs font-medium text-slate-600 bg-slate-50/80 px-3 py-1.5 rounded-lg border border-slate-200/60">
                <i class="fas fa-list text-slate-400 text-[10px]"></i>
                <span>{{ $garansis->count() }} ditampilkan</span>
            </div>
        </div>

        {{-- Responsive Table --}}
        <div class="overflow-hidden">
            {{--
                NOTE: border-collapse is required here. Without it, a border-left
                applied to individual <td> cells can render with visual gaps
                between rows because the default table border model is
                "separate" (cells have spacing around them).
            --}}
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50/60 text-[11px] uppercase tracking-wider text-slate-600 font-semibold border-b border-slate-100/50 sticky top-0">
                    <tr>
                        <th class="px-6 py-3 min-w-[170px]">Nama</th>
                        <th class="px-6 py-3 min-w-[110px]">Invoice</th>
                        <th class="px-6 py-3 min-w-[120px]">No HP</th>
                        <th class="px-6 py-3 min-w-[170px]">Barang</th>
                        <th class="px-6 py-3 min-w-[130px]">Lokasi</th>
                        <th class="px-6 py-3 min-w-[100px]">Status</th>
                        <th class="px-6 py-3 min-w-[120px]">Idle Time</th>
                        <th class="px-6 py-3 min-w-[80px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @forelse($garansis as $garansi)
                        @php
                            $idleDays = $garansi->updated_at
                                ->startOfDay()
                                ->diffInDays(now()->startOfDay());

                            $belumSelesai = strtolower($garansi->status) !== 'selesai';

                            // Split into row background (safe on <tr>) and a
                            // left border (must live on the first <td>,
                            // because browsers do not reliably paint
                            // left/right borders directly on <tr> elements —
                            // that's why previously only the first row in the
                            // whole table appeared to have a colored edge).
                            $rowBg = '';
                            $borderClass = '';
                            $idleBadgeClass = 'bg-slate-100 text-slate-600';

                            if ($belumSelesai) {
                                if ($idleDays >= 2) {
                                    $rowBg = 'bg-rose-50/20';
                                    $borderClass = 'border-l-4 border-rose-500';
                                    $idleBadgeClass = 'bg-rose-100 text-rose-700';
                                } elseif ($idleDays >= 1) {
                                    $rowBg = 'bg-amber-50/20';
                                    $borderClass = 'border-l-4 border-amber-500';
                                    $idleBadgeClass = 'bg-amber-100 text-amber-700';
                                }
                            }
                        @endphp
                        <tr class="group hover:bg-slate-50 transition {{ $rowBg }}">
                            {{-- Nama (carries the SLA left-border indicator) --}}
                            <td class="px-6 py-3 font-semibold text-slate-950 {{ $borderClass }}">
                                <div class="max-w-xs truncate">{{ $garansi->nama }}</div>
                            </td>

                            {{-- Invoice --}}
                            <td class="px-6 py-3 font-mono text-xs text-slate-500">
                                {{ $garansi->invoice_pembelian ?? '-' }}
                            </td>

                            {{-- No HP --}}
                            <td class="px-6 py-3 text-slate-700 text-sm font-medium">
                                {{ $garansi->no_hp }}
                            </td>

                            {{-- Barang --}}
                            <td class="px-6 py-3 text-slate-700">
                                @if($garansi->items->count() > 1)
                                    <div class="max-w-xs truncate">
                                        <span class="text-xs">{{ $garansi->items->first()->nama_barang }}</span>
                                        <span class="text-xs text-slate-400 ml-1">+{{ $garansi->items->count() - 1 }}</span>
                                    </div>
                                @else
                                    <div class="text-xs truncate">{{ $garansi->items->first()->nama_barang ?? '-' }}</div>
                                @endif
                            </td>

                            {{-- Lokasi Chat --}}
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center rounded-lg bg-slate-100/60 px-2.5 py-1 text-xs font-medium text-slate-700 border border-slate-200/60">
                                    {{ ucfirst($garansi->lokasi_chat) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold {{ $garansi->status_color }}">
                                    {{ ucfirst($garansi->status) }}
                                </span>
                            </td>

                            {{-- Idle Time --}}
                            <td class="px-6 py-3">
                                @if($belumSelesai)
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold {{ $idleBadgeClass }}">
                                        <i class="fas fa-hourglass-half mr-1.5 text-[10px]"></i>
                                        {{ $idleDays }} hari
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200/60">
                                        <i class="fas fa-check-circle mr-1.5 text-[10px]"></i>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-3 text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a href="{{ route('garansi.show', $garansi) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200/60 bg-white text-slate-500 hover:text-blue-600 hover:bg-blue-50/30 hover:border-blue-200/60 transition-all duration-200"
                                       title="Lihat detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <a href="{{ route('garansi.edit', $garansi) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200/60 bg-white text-slate-500 hover:text-emerald-600 hover:bg-emerald-50/30 hover:border-emerald-200/60 transition-all duration-200"
                                       title="Edit data">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('garansi.destroy', $garansi) }}" method="POST" class="inline"
                                              onsubmit="event.preventDefault(); confirmDelete(this, 'Data garansi {{ addslashes($garansi->nama) }} akan dihapus permanen. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200/60 bg-white text-slate-500 hover:text-rose-600 hover:bg-rose-50/30 hover:border-rose-200/60 transition-all duration-200"
                                                    title="Hapus data">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="mx-auto max-w-md space-y-4">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100/60 text-slate-300">
                                        <i class="fas fa-inbox text-2xl"></i>
                                    </div>
                                    <div class="space-y-1.5">
                                        <h3 class="text-sm font-semibold text-slate-950">Tidak ada data ditemukan</h3>
                                        <p class="text-sm text-slate-600">
                                            @if(request('search') || request('status'))
                                                Coba ubah kata kunci pencarian atau sesuaikan filter.
                                            @else
                                                Mulai dengan membuat data garansi baru.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex justify-center gap-3">
                                        @if(request('search') || request('status'))
                                            <a href="{{ route('garansi.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 transition-all duration-200">
                                                <i class="fas fa-rotate-right text-xs"></i>
                                                <span>Ulang Filter</span>
                                            </a>
                                        @endif
                                        <a href="{{ route('garansi.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all duration-200">
                                            <i class="fas fa-plus text-xs"></i>
                                            <span>Buat Baru</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($garansis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100/50">
                {{ $garansis->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<script>
    function confirmDelete(form, message) {
        if (confirm(message)) {
            form.submit();
        }
    }
</script>
@endsection