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
        <div class="mt-6 pt-5 border-t border-slate-100 flex overflow-x-auto md:flex-wrap items-center gap-6 text-xs text-slate-600 pb-2">
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

    {{-- Card Grid --}}
    <div>
        <div class="flex items-center justify-between gap-4 mb-5">
            <div class="flex items-center gap-4">
                <div>
                    <h2 class="text-base font-semibold tracking-tight text-slate-950">Daftar Garansi</h2>
                    <div class="mt-1 flex items-center gap-3">
                        <p class="text-sm text-slate-600">{{ $garansis->total() }} total data</p>
                        <div class="h-4 w-px bg-slate-300"></div>
                        @php
                            $totalProses = \App\Models\Garansi::where('status', '!=', 'selesai')->count();
                            $totalSelesai = \App\Models\Garansi::where('status', 'selesai')->count();
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/50">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            {{ $totalProses }} Proses
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/50">
                            <i class="fas fa-check-circle text-[10px]"></i>
                            {{ $totalSelesai }} Selesai
                        </span>
                    </div>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2.5 text-xs font-medium text-slate-600 bg-white/80 backdrop-blur px-3 py-1.5 rounded-lg border border-slate-200/60 shadow-sm">
                <i class="fas fa-th-large text-slate-400 text-[10px]"></i>
                <span>{{ $garansis->count() }} ditampilkan</span>
            </div>
        </div>

        @if($garansis->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4" style="align-items: stretch;">
                @foreach($garansis as $garansi)
                    @php
                        $idleDays = $garansi->updated_at
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay());

                        $belumSelesai = strtolower($garansi->status) !== 'selesai';

                        // SLA border color
                        $slaBorder = 'border-l-slate-200';
                        $slaBg     = '';
                        $idleBadgeClass = 'bg-slate-100 text-slate-600';

                        if ($belumSelesai) {
                            if ($idleDays >= 2) {
                                $slaBorder = 'border-l-rose-500';
                                $slaBg = 'bg-rose-50/30';
                                $idleBadgeClass = 'bg-rose-100 text-rose-700';
                            } elseif ($idleDays >= 1) {
                                $slaBorder = 'border-l-amber-500';
                                $slaBg = 'bg-amber-50/30';
                                $idleBadgeClass = 'bg-amber-100 text-amber-700';
                            }
                        }

                        $slaBarColor = 'bg-slate-200';
                        if ($belumSelesai) {
                            if ($idleDays >= 2) $slaBarColor = 'bg-rose-500';
                            elseif ($idleDays >= 1) $slaBarColor = 'bg-amber-500';
                        } else {
                            $slaBarColor = 'bg-emerald-500';
                        }
                    @endphp
                    <div onclick="window.location='{{ route('garansi.show', $garansi) }}'"
                       class="cursor-pointer group relative rounded-xl border border-slate-200/60 bg-white shadow-sm hover:shadow-md hover:border-slate-300/60 transition-all duration-200 overflow-hidden {{ $slaBg }}"
                       style="display: flex; flex-direction: column; min-height: 150px;">

                        {{-- SLA Left Bar Indicator --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $slaBarColor }}" style="border-radius: 12px 0 0 12px;"></div>

                        {{-- Card Header: Nama + Status --}}
                        <div class="pl-5 pr-4 pt-4 pb-2.5 flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900 truncate group-hover:text-blue-600 transition-colors">
                                    {{ $garansi->nama }}
                                </h3>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    <i class="fas fa-phone text-[9px] mr-1"></i>{{ $garansi->no_hp }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                @if($belumSelesai && $idleDays > 0)
                                    <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-bold {{ $idleBadgeClass }}">
                                        <i class="fas fa-hourglass-half mr-0.5 text-[8px]"></i>
                                        {{ $idleDays }}h
                                    </span>
                                @endif
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold {{ $garansi->status_color }}">
                                    {{ ucfirst($garansi->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body: Info (grows to fill space) --}}
                        <div class="pl-5 pr-4 pb-3 space-y-1.5" style="flex: 1;">
                            {{-- Barang --}}
                            @foreach($garansi->items->take(2) as $item)
                                <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                    <i class="fas fa-box text-[8px] text-slate-300 flex-shrink-0"></i>
                                    <span class="truncate">{{ $item->nama_barang }}</span>
                                    @if($item->serial_number)
                                        <span class="font-mono text-[10px] text-slate-400 flex-shrink-0">{{ Str::limit($item->serial_number, 12) }}</span>
                                    @endif
                                </div>
                            @endforeach
                            @if($garansi->items->count() > 2)
                                <span class="text-[10px] text-slate-400 pl-4">+{{ $garansi->items->count() - 2 }} lainnya</span>
                            @endif

                            {{-- Invoice --}}
                            @if($garansi->invoice_pembelian)
                                <div class="flex items-center gap-1.5 text-xs">
                                    <i class="fas fa-receipt text-[8px] text-slate-300 flex-shrink-0"></i>
                                    <span class="font-mono text-[10px] text-slate-500 truncate">{{ $garansi->invoice_pembelian }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Footer (always pinned to bottom) --}}
                        <div class="pl-5 pr-3 py-2.5 border-t border-slate-100 flex items-center justify-between gap-2 bg-slate-50/40" style="margin-top: auto;">
                            <div class="flex items-center gap-2 min-w-0 text-[10px] text-slate-400">
                                <span class="inline-flex items-center gap-1 bg-white rounded px-1.5 py-0.5 border border-slate-200/60 text-slate-500 font-medium flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-[7px]"></i>
                                    {{ ucfirst($garansi->lokasi_chat) }}
                                </span>
                                <span class="truncate">
                                    <i class="fas fa-clock text-[7px] mr-0.5"></i>
                                    {{ $garansi->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-0.5 flex-shrink-0" onclick="event.preventDefault(); event.stopPropagation();">
                                <a href="{{ route('garansi.edit', $garansi) }}"
                                   class="inline-flex h-6 w-6 items-center justify-center rounded-md text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all"
                                   title="Edit">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('garansi.destroy', $garansi) }}" method="POST" class="inline"
                                          onsubmit="event.preventDefault(); event.stopPropagation(); confirmDelete(this, 'Data garansi {{ addslashes($garansi->nama) }} akan dihapus permanen. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex h-6 w-6 items-center justify-center rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"
                                                title="Hapus">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="rounded-2xl border border-slate-200/40 bg-white shadow-sm px-6 py-14 text-center">
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
            </div>
        @endif

        {{-- Pagination --}}
        @if($garansis->hasPages())
            <div class="mt-5">
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