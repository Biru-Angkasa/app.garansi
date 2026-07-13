@extends('layouts.app')

@section('title', 'Detail Garansi')

@section('content')
@php
    $initials = collect(explode(' ', $garansi->nama))
        ->take(2)
        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
        ->join('');

    $belumSelesai = !in_array(strtolower($garansi->status), ['selesai', 'ditolak']);
    
    // Hitung idle days
    $idleDays = 0;
    if ($belumSelesai) {
        $lastLog = $garansi->whatsappLogs()->latest()->first();
        $baseDate = $lastLog ? $lastLog->created_at : $garansi->created_at;
        $idleDays = max(0, intval($baseDate->diffInDays(now())));
    }

    $idleBadge = 'bg-slate-50 text-slate-600 border border-slate-200';
    if ($idleDays >= 7) {
        $idleBadge = 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse';
    } elseif ($idleDays >= 3) {
        $idleBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
    }
@endphp

@push('styles')
<style>
    .btn-interactive {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-interactive:active {
        transform: scale(0.97);
    }
    .card-interactive {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .modal-backdrop {
        backdrop-filter: blur(8px);
        background-color: rgba(15, 23, 42, 0.3);
    }
    .modal-panel {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-hidden-state {
        opacity: 0;
        transform: scale(0.95) translateY(10px);
        pointer-events: none;
    }
    .spinner-ring {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-right-color: currentColor;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    /* Simple scale animation for conditional fields */
    .animate-in-fast {
        animation: fadeInFast 0.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes fadeInFast {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

{{-- Toast Alert Stack --}}
<div id="toast-stack" class="fixed top-5 right-5 z-[100] flex flex-col gap-3 max-w-sm pointer-events-none"></div>

<div class="container-main max-w-6xl mx-auto p-4 md:p-0 space-y-6">
    
    {{-- Header Breadcrumbs & Quick Back --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
        <nav class="flex text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('garansi.index') }}" class="inline-flex items-center hover:text-slate-800 transition-colors">
                        <i class="fas fa-home mr-2 text-xs"></i>
                        Daftar Garansi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-[10px] mx-1 text-slate-400"></i>
                        <span class="text-slate-800 font-semibold">Detail #{{ $garansi->invoice_pembelian }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <a href="{{ route('garansi.index') }}" class="btn-interactive inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Main Asymmetric Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- Left Column (Main Info, Items, Logs) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Summary/Hero Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200/60 flex items-center justify-center text-lg font-bold text-slate-700 shadow-sm shrink-0">
                            {{ $initials }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-xl font-bold text-slate-900 leading-tight">{{ $garansi->nama }}</h1>
                                @if ($belumSelesai)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-full {{ $idleBadge }}">
                                        <i class="fas fa-clock text-[9px]"></i> {{ $idleDays }} Hari Idle
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm font-mono text-slate-500 mt-1 flex items-center gap-1.5">
                                <i class="fas fa-receipt text-xs text-slate-400"></i> {{ $garansi->invoice_pembelian }}
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold shadow-sm uppercase tracking-wider {{ $garansi->status_color }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $garansi->status_label }}
                        </span>
                    </div>
                </div>

                {{-- Status Progress Tracker --}}
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-2">
                        <span>Progres Klaim Garansi</span>
                        <span class="text-slate-800 font-bold">{{ $garansi->status_progress }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $garansi->status_progress }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Items List Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-box text-slate-400 text-sm"></i>
                        <h2 class="font-bold text-slate-800 text-base">Barang Garansi</h2>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-bold bg-slate-100 text-slate-700 rounded-full">
                        {{ count($garansi->items) }} Item
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($garansi->items as $item)
                    <div class="py-4 first:pt-1 last:pb-1" data-item-row>
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="space-y-1 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-sm text-slate-800">{{ $item->nama_barang }}</span>
                                    @if($item->is_replaced)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 rounded">
                                            <i class="fas fa-exchange-alt"></i> Replaced
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                    <span class="font-mono">SN: {{ $item->serial_number ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="w-full sm:w-auto shrink-0 min-w-[200px] mt-1 sm:mt-0">
                                {{-- Display Replacement SN --}}
                                <div class="sn-baru-display flex items-center justify-between gap-4 {{ !$item->serial_number_baru ? 'hidden' : '' }}">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">SN Pengganti</span>
                                        <span class="sn-baru-text text-xs font-bold font-mono text-emerald-600">{{ $item->serial_number_baru }}</span>
                                        <span class="sn-baru-tanggal text-[9px] text-slate-400">
                                            {{ $item->replaced_at ? $item->replaced_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '' }} WIB
                                        </span>
                                    </div>
                                    <button type="button" class="btn-edit-sn btn-interactive text-[10px] font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100/80 px-2 py-1 rounded transition-colors">
                                        Edit SN
                                    </button>
                                </div>

                                {{-- Edit/Input Replacement SN --}}
                                <div class="sn-baru-form {{ $item->serial_number_baru ? 'hidden' : '' }}">
                                    <div class="flex items-center gap-2">
                                        <input type="text" class="sn-baru-input flex-1 px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 font-mono" placeholder="Masukkan SN baru..." value="{{ $item->serial_number_baru }}">
                                        <button type="button" data-url="{{ route('garansi.items.replace-sn', [$garansi->id, $item->id]) }}" class="btn-save-sn btn-interactive bg-slate-900 hover:bg-slate-800 text-white px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1">
                                            <i class="fas fa-save text-[10px]"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- WhatsApp Logs Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fab fa-whatsapp text-slate-400 text-base"></i>
                        <h2 class="font-bold text-slate-800 text-base">Log Notifikasi WhatsApp</h2>
                    </div>
                    <button type="button" id="btn-send-wa" class="btn-interactive inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg shadow-sm">
                        <i class="fas fa-paper-plane text-[10px]"></i> Kirim Manual
                    </button>
                </div>

                {{-- Form Custom WhatsApp Message --}}
                <div id="wa-form" class="hidden mb-6 p-4 bg-slate-50 border border-slate-200/60 rounded-xl animate-in-fast">
                    <label class="block text-xs uppercase tracking-wide text-slate-500 font-bold mb-2">Pesan WhatsApp Kustom</label>
                    <textarea id="wa-message" rows="3" placeholder="Tulis pesan untuk dikirim langsung ke customer..." class="w-full border border-slate-200 rounded-lg p-3 text-xs outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" id="btn-cancel-wa" class="btn-interactive px-3 py-1.5 text-xs font-semibold text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Batal</button>
                        <button type="button" id="btn-send-wa-confirm" class="btn-interactive px-3.5 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm flex items-center gap-1">
                            <i class="fas fa-paper-plane text-[9px]"></i> Kirim Pesan
                        </button>
                    </div>
                </div>

                {{-- WhatsApp Logs List --}}
                @if($garansi->whatsappLogs->isEmpty())
                <div class="py-10 text-center">
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-500">Belum ada log WhatsApp untuk garansi ini.</p>
                </div>
                @else
                <div class="relative pl-5 space-y-5 before:absolute before:left-[9px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-slate-100">
                    @foreach($garansi->whatsappLogs as $log)
                    <div class="relative">
                        {{-- Timeline Marker --}}
                        <div class="absolute -left-[21px] top-1 w-5 h-5 rounded-full border border-slate-200 bg-white flex items-center justify-center text-[8px] text-slate-500 shadow-sm">
                            @if($log->status_kirim === 'success')
                                <i class="fas fa-check text-emerald-600"></i>
                            @elseif($log->status_kirim === 'failed')
                                <i class="fas fa-times text-rose-500"></i>
                            @else
                                <i class="fas fa-paper-plane text-slate-400"></i>
                            @endif
                        </div>
                        
                        {{-- Log bubble --}}
                        <div class="bg-slate-50/50 border border-slate-200/60 rounded-xl p-4 space-y-2">
                            <div class="flex items-center justify-between text-[10px] text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-slate-700 uppercase">{{ $log->tipe }}</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span>{{ $log->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>
                                @if($log->status_kirim === 'failed')
                                    <button type="button" data-url="{{ route('garansi.resend-wa', [$garansi->id, $log->id]) }}" class="resend-wa-btn btn-interactive inline-flex items-center gap-1 text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100/80 px-2 py-0.5 rounded text-[9px] font-bold transition-all">
                                        <i class="fas fa-rotate-right"></i> Kirim Ulang
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold rounded bg-emerald-50 text-emerald-700">Terkirim</span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-slate-600 whitespace-pre-line leading-relaxed font-sans">{{ $log->pesan }}</p>
                            
                            @if($log->image_data)
                            <div class="pt-1.5">
                                <div class="relative group inline-block rounded-lg overflow-hidden border border-slate-200 max-w-[140px] shadow-sm">
                                    <img src="{{ $log->image_data }}" class="max-h-24 object-cover transition-transform group-hover:scale-105 duration-200" alt="Bukti WhatsApp">
                                    <a href="{{ $log->image_data }}" target="_blank" class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity duration-200">
                                        <i class="fas fa-expand text-xs"></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        {{-- Right Column (Sidebar Actions, Customer Details) --}}
        <div class="space-y-6 lg:sticky lg:top-6">
            
            {{-- Quick Action Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-3">
                <button type="button" id="btn-open-status-modal" class="btn-interactive w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-md transition-all">
                    <i class="fas fa-sync-alt text-xs"></i> Update Status Garansi
                </button>
                
                <a href="{{ route('garansi.edit', $garansi) }}" class="btn-interactive w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-sm shadow-sm transition-all">
                    <i class="fas fa-edit text-xs"></i> Edit Data Garansi
                </a>
            </div>

            {{-- Customer & Invoice Information --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                <div class="pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Customer</h3>
                </div>

                <div class="space-y-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama Lengkap</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $garansi->nama }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Nomor WhatsApp</span>
                        <div class="flex items-center justify-between gap-2 p-2 bg-slate-50 border border-slate-200/60 rounded-xl">
                            <span class="text-xs font-mono text-slate-600 font-bold">{{ $garansi->no_hp }}</span>
                            <button type="button" data-copy="{{ $garansi->no_hp }}" class="copy-btn btn-interactive text-slate-400 hover:text-slate-600 w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm shrink-0">
                                <i class="far fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Marketplace</span>
                            <span class="text-xs font-semibold text-slate-800 flex items-center gap-1 mt-0.5">
                                <i class="fas fa-store text-[10px] text-slate-400"></i>
                                {{ $garansi->nama_marketplace ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Lokasi Chat</span>
                            <span class="text-xs font-semibold text-slate-800 flex items-center gap-1 mt-0.5">
                                <i class="fab fa-whatsapp text-[10px] text-slate-400"></i>
                                {{ $garansi->lokasi_chat ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-50">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Tanggal Beli</span>
                            <span class="text-xs font-semibold text-slate-800 block mt-0.5">
                                {{ $garansi->tanggal_beli ? \Carbon\Carbon::parse($garansi->tanggal_beli)->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Tanggal Sampai</span>
                            <span class="text-xs font-semibold text-slate-800 block mt-0.5">
                                {{ $garansi->tanggal_sampai ? \Carbon\Carbon::parse($garansi->tanggal_sampai)->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Case & Condition Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                <div class="pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Kondisi & Keluhan</h3>
                </div>

                <div class="space-y-3.5">
                    <div class="p-3.5 bg-rose-50/50 border border-rose-100 rounded-xl">
                        <span class="text-[10px] text-rose-500 font-bold uppercase block mb-1">Keluhan / Kerusakan</span>
                        <p class="text-xs text-rose-950 font-medium leading-relaxed whitespace-pre-line">{{ $garansi->kerusakan ?? '-' }}</p>
                    </div>

                    <div class="p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl">
                        <span class="text-[10px] text-slate-500 font-bold uppercase block mb-1">Kelengkapan Barang</span>
                        <p class="text-xs text-slate-800 font-medium leading-relaxed whitespace-pre-line">{{ $garansi->kelengkapan_barang ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Additional Technical Info Card --}}
            @if($garansi->resi_pengiriman || $garansi->sn_pengganti || $garansi->catatan)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                <div class="pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Tambahan</h3>
                </div>

                <div class="space-y-3">
                    @if($garansi->resi_pengiriman)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Resi Pengiriman</span>
                        <span class="text-xs font-semibold font-mono text-slate-800">{{ $garansi->resi_pengiriman }}</span>
                    </div>
                    @endif

                    @if($garansi->sn_pengganti)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">SN Pengganti Global</span>
                        <span class="text-xs font-semibold font-mono text-emerald-600">{{ $garansi->sn_pengganti }}</span>
                    </div>
                    @endif

                    @if($garansi->catatan)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Catatan Internal Teknisi</span>
                        <div class="p-3 bg-amber-50/50 border border-amber-100 rounded-xl text-xs text-amber-900 leading-relaxed font-sans">
                            {{ $garansi->catatan }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ===== MODAL UPDATE STATUS DINAMIS - LIQUID GLASS & DRAGGABLE ===== --}}
@php
    $statusMeta = [
        'diterima'        => ['icon' => 'fa-inbox',              'active' => 'bg-slate-900 border-slate-900 text-white shadow-sm',      'idle' => 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'],
        'diperiksa'       => ['icon' => 'fa-magnifying-glass',   'active' => 'bg-blue-600 border-blue-600 text-white shadow-sm',        'idle' => 'border-slate-200 text-slate-600 hover:border-blue-300 hover:bg-blue-50'],
        'repair'          => ['icon' => 'fa-screwdriver-wrench', 'active' => 'bg-amber-500 border-amber-500 text-white shadow-sm',      'idle' => 'border-slate-200 text-slate-600 hover:border-amber-300 hover:bg-amber-50'],
        'replace'         => ['icon' => 'fa-shuffle',            'active' => 'bg-indigo-600 border-indigo-600 text-white shadow-sm',    'idle' => 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-indigo-50'],
        'to distribution' => ['icon' => 'fa-warehouse',          'active' => 'bg-slate-800 border-slate-800 text-white shadow-sm',      'idle' => 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'],
        'pengiriman'      => ['icon' => 'fa-truck-fast',         'active' => 'bg-sky-500 border-sky-500 text-white shadow-sm',          'idle' => 'border-slate-200 text-slate-600 hover:border-sky-300 hover:bg-sky-50'],
        'selesai'         => ['icon' => 'fa-circle-check',       'active' => 'bg-emerald-600 border-emerald-600 text-white shadow-sm',  'idle' => 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50'],
    ];
    $defaultMeta = ['icon' => 'fa-circle-dot', 'active' => 'bg-blue-600 border-blue-600 text-white shadow-sm', 'idle' => 'border-slate-200 text-slate-600 hover:border-blue-300 hover:bg-blue-50'];
@endphp

<div id="modal-status-dynamic" class="modal-backdrop fixed inset-0 z-50 hidden flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div id="modal-status-panel" class="modal-panel modal-hidden-state bg-white/95 border border-white/20 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col cursor-grab active:cursor-grabbing" style="touch-action: none;">

        {{-- Header (Draggable Handle) --}}
        <div class="relative bg-slate-900 px-6 py-5 shrink-0 overflow-hidden modal-header cursor-grab active:cursor-grabbing" style="user-select: none;">
            <div class="relative flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                        <i class="fas fa-sync-alt text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white leading-snug">Update Status Garansi</h3>
                        <p class="text-slate-400 text-[10px] mt-0.5">Seret panel ini, perbarui status, & kirim WhatsApp</p>
                    </div>
                </div>
                <button type="button" class="modal-close w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all duration-200 shrink-0">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Content Scrollable --}}
        <form id="form-update-status-dynamic" class="px-6 pb-6 pt-5 overflow-y-auto flex-1 space-y-5">
            @csrf

            <div>
                <label class="text-[10px] uppercase tracking-wide text-slate-500 font-bold block mb-2.5">Pilih Status Baru</label>
                <select id="status-select-dynamic" name="status" class="hidden">
                    @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ $garansi->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <div id="status-chip-group" class="grid grid-cols-2 gap-2">
                    @foreach($statusList as $status)
                    @php $meta = $statusMeta[strtolower($status)] ?? $defaultMeta; @endphp
                    <button type="button"
                        class="status-chip btn-interactive flex items-center gap-2 rounded-xl border px-3 py-2.5 text-xs font-semibold text-left shadow-sm transition-all duration-200 {{ $garansi->status === $status ? $meta['active'] : $meta['idle'] }}"
                        data-value="{{ $status }}" data-active-class="{{ $meta['active'] }}" data-idle-class="{{ $meta['idle'] }}">
                        <i class="fas {{ $meta['icon'] }} text-xs w-4 text-center"></i>
                        <span class="truncate">{{ ucfirst($status) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Conditional Fields --}}
            <div id="field-replace" class="hidden animate-in-fast">
                <label class="text-[10px] uppercase tracking-wide text-indigo-600 font-bold flex items-center gap-1.5 mb-2">
                    <i class="fas fa-microchip"></i> Serial Number Pengganti *
                </label>
                <input type="text" name="sn_pengganti" class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-3.5 py-2.5 outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-xs transition-all" placeholder="Masukkan SN baru untuk unit replacement...">
            </div>

            <div id="field-camera" class="hidden animate-in-fast">
                <label class="text-[10px] uppercase tracking-wide text-slate-500 font-bold flex items-center gap-1.5 mb-2">
                    <i class="fas fa-camera"></i> Bukti Foto (Opsional)
                </label>
                <div id="camera-card" class="border border-dashed border-slate-300 bg-slate-50/80 rounded-xl p-5 text-center transition-all hover:border-slate-400">
                    <video id="camera-stream" class="w-full rounded-lg hidden shadow-sm mb-3"></video>
                    <canvas id="camera-canvas" class="hidden"></canvas>
                    <img id="photo-preview" class="w-full rounded-lg hidden mb-3 shadow-sm ring-1 ring-slate-100 object-cover max-h-48" src="">

                    <input type="hidden" name="bukti_foto_data" id="bukti-foto-data">
                    <input type="file" id="bukti-foto-file" class="hidden" accept="image/*" capture="environment">

                    <div id="camera-buttons">
                        <div id="camera-placeholder" class="mb-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200/60 flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-camera text-slate-400 text-sm"></i>
                            </div>
                            <p class="text-[10px] text-slate-500 font-medium">Unggah atau ambil foto sebagai bukti pengerjaan</p>
                        </div>
                        <div class="flex flex-wrap justify-center gap-2">
                            <button type="button" id="btn-start-camera" class="btn-interactive bg-white border border-slate-200 hover:border-slate-300 text-slate-700 px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm transition-all">
                                <i class="fas fa-video mr-1.5"></i> Kamera Langsung
                            </button>
                            <button type="button" id="btn-upload-photo" class="btn-interactive bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm transition-all">
                                <i class="fas fa-file-image mr-1.5"></i> Pilih File
                            </button>
                            <button type="button" id="btn-take-photo" class="btn-interactive bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-2 rounded-lg text-xs font-bold hidden shadow-sm transition-all">
                                <i class="fas fa-circle mr-1.5"></i> Jepret Foto
                            </button>
                            <button type="button" id="btn-retake-photo" class="btn-interactive bg-white border border-slate-200 hover:border-slate-300 text-slate-700 px-3.5 py-2 rounded-lg text-xs font-bold hidden shadow-sm transition-all">
                                <i class="fas fa-undo mr-1.5"></i> Ambil Ulang
                            </button>
                        </div>
                    </div>
                    <p id="camera-status" class="text-[9px] text-slate-400 mt-2 font-medium"></p>
                </div>
            </div>

            <div>
                <label class="text-[10px] uppercase tracking-wide text-slate-500 font-bold flex items-center gap-1.5 mb-2">
                    <i class="fas fa-sticky-note"></i> Catatan Internal
                </label>
                <textarea name="catatan" rows="3" placeholder="Tambahkan catatan teknisi (opsional)..." class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-xs resize-none transition-all">{{ $garansi->catatan }}</textarea>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" class="modal-close btn-interactive flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 font-bold text-slate-600 text-xs transition-all">Batal</button>
                <button type="submit" class="btn-submit-status btn-interactive flex-1 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-1.5">
                    <i class="fas fa-check text-[10px]"></i>
                    <span class="btn-submit-label">Simpan Status</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        // Element Helpers
        const $ = (selector, scope = document) => scope.querySelector(selector);
        const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
        const csrf = $('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // Custom Helper Functions
        async function parseResponse(response, fallbackMessage) {
            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json')
                ? await response.json()
                : { message: await response.text() };

            if (!response.ok) {
                throw new Error(data.message || fallbackMessage);
            }

            return data;
        }

        function setLoading(button, isLoading, loadingText = 'Memproses...') {
            if (!button) return;

            if (isLoading) {
                button.dataset.originalHtml = button.innerHTML;
                button.disabled = true;
                button.innerHTML = `<span class="spinner-ring"></span><span>${loadingText}</span>`;
                return;
            }

            button.disabled = false;
            button.innerHTML = button.dataset.originalHtml || button.innerHTML;
            delete button.dataset.originalHtml;
        }

        function showToast(message, type = 'success') {
            const stack = $('#toast-stack');
            if (!stack) return;

            const palette = {
                success: { bg: 'bg-slate-900 border border-slate-800/80', icon: 'fa-circle-check text-emerald-500' },
                error: { bg: 'bg-rose-950 border border-rose-900/80', icon: 'fa-circle-exclamation text-rose-500' },
                info: { bg: 'bg-slate-900 border border-slate-800/80', icon: 'fa-circle-info text-blue-400' },
            }[type] || { bg: 'bg-slate-900 border border-slate-800/80', icon: 'fa-circle-info text-slate-400' };

            const toast = document.createElement('div');
            toast.className = `${palette.bg} text-white rounded-xl shadow-lg px-4 py-3 flex items-start gap-2.5 text-xs font-semibold backdrop-blur-md transition-all duration-300 transform translate-x-10 opacity-0`;
            toast.innerHTML = `<i class="fas ${palette.icon} mt-0.5 shrink-0"></i><span class="flex-1"></span>`;
            toast.querySelector('span').textContent = message;
            stack.appendChild(toast);

            // Reflow to animate
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-10', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-x-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        async function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return;
            }

            const input = document.createElement('textarea');
            input.value = text;
            input.setAttribute('readonly', '');
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            input.remove();
        }

        // Copy Event Listeners
        $$('.copy-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const text = this.dataset.copy || '';
                const icon = $('i', this);

                try {
                    await copyText(text);
                    showToast(`Disalin ke papan klip: ${text}`, 'info');
                    icon?.classList.replace('fa-copy', 'fa-check');
                    setTimeout(() => icon?.classList.replace('fa-check', 'fa-copy'), 1500);
                } catch {
                    showToast('Gagal menyalin nomor.', 'error');
                }
            });
        });

        // Save Item Replacement SN
        $$('.btn-save-sn').forEach(button => {
            button.addEventListener('click', async function() {
                const row = this.closest('[data-item-row]');
                const input = $('.sn-baru-input', row);
                const snBaru = input?.value.trim();

                if (!snBaru) {
                    showToast('Serial number baru tidak boleh kosong.', 'error');
                    input?.focus();
                    return;
                }

                setLoading(this, true, 'Simpan...');

                try {
                    const response = await fetch(this.dataset.url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ serial_number_baru: snBaru }),
                    });
                    const data = await parseResponse(response, 'Gagal menyimpan SN baru.');

                    if (!data.success) {
                        throw new Error(data.message || 'Gagal menyimpan SN baru.');
                    }

                    $('.sn-baru-text', row).textContent = data.item?.serial_number_baru || snBaru;
                    $('.sn-baru-tanggal', row).textContent = data.item?.replaced_at
                        ? new Date(data.item.replaced_at).toLocaleString('id-ID')
                        : '';
                    $('.sn-baru-form', row)?.classList.add('hidden');
                    $('.sn-baru-display', row)?.classList.remove('hidden');
                    showToast('SN baru tersimpan. Notifikasi WhatsApp sedang dikirim ke customer.', 'success');
                } catch (err) {
                    showToast(err.message || 'Gagal menyimpan SN baru.', 'error');
                } finally {
                    setLoading(this, false);
                }
            });
        });

        // Edit Item Replacement SN
        $$('.btn-edit-sn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('[data-item-row]');
                $('.sn-baru-display', row)?.classList.add('hidden');
                $('.sn-baru-form', row)?.classList.remove('hidden');
                $('.sn-baru-input', row)?.focus();
            });
        });

        // Resend WhatsApp Logs
        $$('.resend-wa-btn').forEach(button => {
            button.addEventListener('click', async function() {
                setLoading(this, true, 'Mengirim...');

                try {
                    const response = await fetch(this.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({}),
                    });
                    const data = await parseResponse(response, 'Gagal mengirim ulang WhatsApp.');

                    if (!data.success) {
                        throw new Error(data.message || 'Gagal mengirim ulang WhatsApp.');
                    }

                    showToast('Pesan berhasil dikirim ulang!', 'success');
                    setTimeout(() => location.reload(), 700);
                } catch (err) {
                    showToast(err.message || 'Gagal mengirim ulang WhatsApp.', 'error');
                } finally {
                    setLoading(this, false);
                }
            });
        });

        // Send Custom WhatsApp Messages
        const btnSendWa = $('#btn-send-wa');
        const waForm = $('#wa-form');
        const waMessage = $('#wa-message');

        btnSendWa?.addEventListener('click', () => {
            waForm?.classList.toggle('hidden');
            if (!waForm?.classList.contains('hidden')) waMessage?.focus();
        });

        $('#btn-cancel-wa')?.addEventListener('click', () => {
            waForm?.classList.add('hidden');
            if (waMessage) waMessage.value = '';
        });

        $('#btn-send-wa-confirm')?.addEventListener('click', async function() {
            const pesan = waMessage?.value.trim();

            if (!pesan) {
                showToast('Pesan tidak boleh kosong.', 'error');
                waMessage?.focus();
                return;
            }

            setLoading(this, true, 'Mengirim...');

            try {
                const response = await fetch('{{ route("garansi.send-wa", $garansi) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ pesan }),
                });
                const data = await parseResponse(response, 'Gagal mengirim pesan.');

                if (!data.success) {
                    throw new Error(data.message || 'Gagal mengirim pesan.');
                }

                showToast('Pesan berhasil dikirim!', 'success');
                setTimeout(() => location.reload(), 700);
            } catch (err) {
                showToast(err.message || 'Gagal mengirim pesan.', 'error');
            } finally {
                setLoading(this, false);
            }
        });

        // ============ STATUS MODAL SCRIPTS ============
        const modalStatus = $('#modal-status-dynamic');
        const modalPanel = $('#modal-status-panel');
        const selectStatus = $('#status-select-dynamic');
        const formStatus = $('#form-update-status-dynamic');
        const statusChips = $$('.status-chip');
        const fieldReplace = $('#field-replace');
        const fieldCamera = $('#field-camera');
        const inputSnPengganti = $('input[name="sn_pengganti"]', formStatus || document);
        const inputCatatan = $('textarea[name="catatan"]', formStatus || document);

        function selectedStatus() {
            return (selectStatus?.value || '').toLowerCase();
        }

        function syncChipHighlight() {
            statusChips.forEach(chip => {
                const isActive = chip.dataset.value === selectStatus?.value;
                chip.className = `status-chip btn-interactive flex items-center gap-2 rounded-xl border px-3 py-2.5 text-xs font-semibold text-left transition-all duration-200 ${isActive ? chip.dataset.activeClass : chip.dataset.idleClass}`;
            });
        }

        function toggleField(field, isVisible) {
            if (field) {
                if (isVisible) {
                    field.classList.remove('hidden');
                } else {
                    field.classList.add('hidden');
                }
            }
        }

        function toggleStatusFields() {
            const status = selectedStatus();
            
            // Status Replace: butuh SN
            const needsSn = status === 'replace';
            
            // Status yang butuh camera: pengiriman, repair, to distribution
            const needsCamera = ['repair', 'to distribution', 'pengiriman'].includes(status);

            toggleField(fieldReplace, needsSn);
            toggleField(fieldCamera, needsCamera);

            if (inputSnPengganti) inputSnPengganti.required = needsSn;
            if (!needsCamera) resetCameraPreview();
        }

        statusChips.forEach(chip => {
            chip.addEventListener('click', () => {
                if (!selectStatus) return;
                selectStatus.value = chip.dataset.value;
                syncChipHighlight();
                toggleStatusFields();
            });
        });

        function openModal() {
            if (!modalStatus || !modalPanel) return;
            modalStatus.classList.remove('hidden');
            modalStatus.classList.add('flex');
            modalStatus.classList.remove('pointer-events-none');
            document.body.classList.add('overflow-hidden');
            
            // Trigger animation frame
            requestAnimationFrame(() => {
                modalStatus.classList.remove('opacity-0');
                modalPanel.classList.remove('modal-hidden-state');
            });
            syncChipHighlight();
            toggleStatusFields();
            resetDragState();
        }

        function closeModal() {
            if (!modalStatus || !modalPanel) return;
            modalStatus.classList.add('opacity-0');
            modalPanel.classList.add('modal-hidden-state');
            modalStatus.classList.add('pointer-events-none');
            setTimeout(() => {
                modalStatus.classList.add('hidden');
                modalStatus.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }, 300);
            stopCamera();
        }

        $('#btn-open-status-modal')?.addEventListener('click', openModal);
        $$('.modal-close').forEach(btn => btn.addEventListener('click', closeModal));
        modalStatus?.addEventListener('click', e => {
            if (e.target === modalStatus) closeModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && !modalStatus?.classList.contains('hidden')) closeModal();
        });
        selectStatus?.addEventListener('change', () => {
            syncChipHighlight();
            toggleStatusFields();
        });

        // ============ CAMERA MANAGEMENT ============
        let cameraStream = null;
        const video = $('#camera-stream');
        const canvas = $('#camera-canvas');
        const photoPreview = $('#photo-preview');
        const photoData = $('#bukti-foto-data');
        const photoFile = $('#bukti-foto-file');
        const cameraStatus = $('#camera-status');
        const cameraPlaceholder = $('#camera-placeholder');
        const btnStartCamera = $('#btn-start-camera');
        const btnUploadPhoto = $('#btn-upload-photo');
        const btnTakePhoto = $('#btn-take-photo');
        const btnRetakePhoto = $('#btn-retake-photo');

        function setCameraStatus(message) {
            if (cameraStatus) cameraStatus.textContent = message;
        }

        function resetCameraPreview() {
            stopCamera();
            if (photoPreview) {
                photoPreview.src = '';
                photoPreview.classList.add('hidden');
            }
            if (photoData) photoData.value = '';
            cameraPlaceholder?.classList.remove('hidden');
            btnStartCamera?.classList.remove('hidden');
            btnUploadPhoto?.classList.remove('hidden');
            btnTakePhoto?.classList.add('hidden');
            btnRetakePhoto?.classList.add('hidden');
            if (photoFile) photoFile.value = '';
            setCameraStatus('');
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            if (video) {
                video.pause();
                video.srcObject = null;
                video.classList.add('hidden');
            }
        }

        async function startCamera() {
            if (!navigator.mediaDevices?.getUserMedia) {
                photoFile?.click();
                setCameraStatus('Kamera tidak didukung browser ini. Menggunakan pemilih file.');
                return;
            }

            try {
                stopCamera();
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false,
                });
                video.srcObject = cameraStream;
                await video.play();
                video.classList.remove('hidden');
                cameraPlaceholder?.classList.add('hidden');
                btnStartCamera?.classList.add('hidden');
                btnUploadPhoto?.classList.add('hidden');
                btnTakePhoto?.classList.remove('hidden');
                btnRetakePhoto?.classList.add('hidden');
                setCameraStatus('Kamera aktif. Arahkan ke objek.');
            } catch (err) {
                photoFile?.click();
                showToast('Tidak dapat memuat kamera. Silakan pilih dari file.', 'info');
            }
        }

        btnStartCamera?.addEventListener('click', startCamera);
        btnUploadPhoto?.addEventListener('click', () => photoFile?.click());

        photoFile?.addEventListener('change', () => {
            const file = photoFile.files?.[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                showToast('File harus berupa gambar.', 'error');
                photoFile.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = event => {
                const dataUrl = event.target.result;
                if (photoPreview) {
                    photoPreview.src = dataUrl;
                    photoPreview.classList.remove('hidden');
                }
                if (photoData) photoData.value = dataUrl;

                stopCamera();
                cameraPlaceholder?.classList.add('hidden');
                btnStartCamera?.classList.add('hidden');
                btnUploadPhoto?.classList.add('hidden');
                btnTakePhoto?.classList.add('hidden');
                btnRetakePhoto?.classList.remove('hidden');
                setCameraStatus('Foto terlampir.');
            };
            reader.onerror = () => showToast('Gagal memuat file gambar.', 'error');
            reader.readAsDataURL(file);
        });

        btnTakePhoto?.addEventListener('click', () => {
            if (!video?.videoWidth || !canvas) {
                showToast('Kamera belum siap.', 'error');
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.82);
            if (photoPreview) {
                photoPreview.src = dataUrl;
                photoPreview.classList.remove('hidden');
            }
            if (photoData) photoData.value = dataUrl;

            stopCamera();
            btnTakePhoto.classList.add('hidden');
            btnUploadPhoto?.classList.add('hidden');
            btnRetakePhoto?.classList.remove('hidden');
            setCameraStatus('Foto ditangkap.');
        });

        btnRetakePhoto?.addEventListener('click', async () => {
            if (photoPreview) {
                photoPreview.src = '';
                photoPreview.classList.add('hidden');
            }
            if (photoData) photoData.value = '';
            if (photoFile) {
                photoFile.value = '';
                photoFile.click();
                return;
            }
            await startCamera();
        });

        formStatus?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const status = selectedStatus();
            if (status === 'replace' && !inputSnPengganti?.value.trim()) {
                showToast('Serial number pengganti wajib diisi.', 'error');
                inputSnPengganti?.focus();
                return;
            }

            const btn = $('.btn-submit-status', this);
            setLoading(btn, true, 'Menyimpan...');

            const dataPayload = {
                status: selectStatus?.value,
                catatan: inputCatatan?.value || '',
                sn_pengganti: inputSnPengganti?.value.trim() || null,
                bukti_foto_data: photoData?.value || null,
            };

            try {
                const response = await fetch('{{ route("garansi.status", $garansi) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(dataPayload),
                });
                const data = await parseResponse(response, 'Gagal menyimpan status.');

                if (!data.success) {
                    throw new Error(data.message || 'Gagal menyimpan status.');
                }

                showToast('Status berhasil diperbarui & WhatsApp terkirim!', 'success');
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                showToast(err.message || 'Gagal menyimpan status.', 'error');
            } finally {
                setLoading(btn, false);
            }
        });

        // ============ DRAGGABLE PANEL LOGIC ============
        let isDragging = false;
        let currentX = 0;
        let currentY = 0;
        let initialX = 0;
        let initialY = 0;
        let xOffset = 0;
        let yOffset = 0;

        function resetDragState() {
            isDragging = false;
            currentX = 0;
            currentY = 0;
            initialX = 0;
            initialY = 0;
            xOffset = 0;
            yOffset = 0;
            if (modalPanel) {
                modalPanel.style.transform = 'translate(0, 0)';
            }
        }

        function dragStart(e) {
            if (e.button !== 0) return; // Left click only
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
            isDragging = true;
            if (modalPanel) {
                modalPanel.style.cursor = 'grabbing';
            }
        }

        function dragEnd(e) {
            initialX = currentX;
            initialY = currentY;
            isDragging = false;
            if (modalPanel) {
                modalPanel.style.cursor = 'grab';
            }
        }

        function drag(e) {
            if (!isDragging || !modalPanel) return;
            e.preventDefault();

            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;

            xOffset = currentX;
            yOffset = currentY;

            setTranslate(currentX, currentY, modalPanel);
        }

        function setTranslate(xPos, yPos, el) {
            el.style.transform = `translate(${xPos}px, ${yPos}px)`;
        }

        const modalHeader = $('.modal-header', modalPanel);
        if (modalHeader) {
            modalHeader.addEventListener('mousedown', dragStart);
            modalHeader.style.cursor = 'grab';
        }

        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', dragEnd);

        toggleStatusFields();
    })();
</script>
@endpush