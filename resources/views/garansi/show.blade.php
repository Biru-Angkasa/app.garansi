@extends('layouts.app')
@section('title', 'Detail Garansi')

@section('content')
@php
    $initials = collect(explode(' ', trim($garansi->nama)))
        ->filter()
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->take(2)
        ->implode('');

    $idleDays = (int) $garansi->updated_at->copy()->startOfDay()->diffInDays(now()->startOfDay());
    $belumSelesai = strtolower($garansi->status) !== 'selesai';
    $idleBadge = 'bg-slate-100 text-slate-500';
    if ($belumSelesai && $idleDays >= 2) {
        $idleBadge = 'bg-rose-100 text-rose-700';
    } elseif ($belumSelesai && $idleDays >= 1) {
        $idleBadge = 'bg-amber-100 text-amber-700';
    }

    $waTerkirim = $garansi->whatsappLogs->where('status_kirim', 'terkirim')->count();
@endphp

@push('styles')
<style>
    :root {
        --transition-smooth: cubic-bezier(0.4, 0, 0.2, 1);
        --transition-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        --transition-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    /* ============ KEYFRAME ANIMATIONS ============ */
    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from { 
            opacity: 0; 
            transform: scale(0.92) translateY(10px); 
        }
        to { 
            opacity: 1; 
            transform: scale(1) translateY(0); 
        }
    }

    @keyframes shimmer {
        0% { 
            background-position: -400px 0; 
        }
        100% { 
            background-position: 400px 0; 
        }
    }

    @keyframes pulseSoft {
        0%, 100% { 
            box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.35); 
        }
        50% { 
            box-shadow: 0 0 0 8px rgba(244, 63, 94, 0); 
        }
    }

    @keyframes toastIn {
        from { 
            opacity: 0; 
            transform: translateY(-12px) scale(0.92); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }

    @keyframes stripe {
        0% { 
            background-position: 0 0; 
        }
        100% { 
            background-position: 40px 0; 
        }
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4), inset 0 0 20px rgba(255, 255, 255, 0.1);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0), inset 0 0 30px rgba(255, 255, 255, 0.2);
        }
    }

    @keyframes floatUp {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-8px);
        }
    }

    @keyframes ringPop {
        0% { 
            opacity: 0.6; 
            transform: scale(0.8); 
        }
        100% { 
            opacity: 0; 
            transform: scale(1.8); 
        }
    }

    @keyframes spin {
        to { 
            transform: rotate(360deg); 
        }
    }

    /* ============ BASE ANIMATIONS ============ */
    .animate-in {
        animation: fadeInUp 0.6s var(--transition-smooth) both;
    }

    .animate-in-fast {
        animation: fadeInUp 0.4s var(--transition-smooth) both;
    }

    .stagger-1 { animation-delay: 0.05s; }
    .stagger-2 { animation-delay: 0.1s; }
    .stagger-3 { animation-delay: 0.15s; }
    .stagger-4 { animation-delay: 0.2s; }
    .stagger-5 { animation-delay: 0.25s; }

    /* ============ CARD HOVER EFFECTS ============ */
    .card-interactive {
        position: relative;
        transition: all 0.4s var(--transition-smooth);
        border-color: rgba(148, 163, 184, 0.2);
    }

    .card-interactive::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        opacity: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), transparent);
        transition: opacity 0.4s var(--transition-smooth);
        pointer-events: none;
    }

    .card-interactive:hover {
        border-color: rgba(59, 130, 246, 0.3);
        transform: translateY(-6px);
        box-shadow: 
            0 20px 40px rgba(15, 23, 42, 0.08),
            0 0 1px rgba(59, 130, 246, 0.4);
    }

    .card-interactive:hover::before {
        opacity: 1;
    }

    /* ============ HERO CARD ============ */
    .hero-card {
        position: relative;
        overflow: hidden;
    }

    .hero-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), rgba(255, 255, 255, 0.1), transparent 50%);
        opacity: 0;
        transition: opacity 0.6s var(--transition-smooth);
        pointer-events: none;
    }

    .hero-card:hover::after {
        opacity: 1;
    }

    /* ============ AVATAR EFFECTS ============ */
    .avatar-interactive {
        position: relative;
        animation: floatUp 3s ease-in-out infinite;
    }

    .avatar-interactive::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 1.25rem;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(99, 102, 241, 0.3));
        opacity: 0;
        transition: opacity 0.4s var(--transition-smooth);
        z-index: -1;
    }

    .card-interactive:hover .avatar-interactive::after {
        opacity: 1;
    }

    /* ============ BUTTON INTERACTIONS ============ */
    .btn-interactive {
        position: relative;
        overflow: hidden;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-interactive::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s var(--transition-smooth);
    }

    .btn-interactive:hover::before {
        transform: translateX(100%);
    }

    .btn-interactive:active {
        transform: scale(0.96);
    }

    /* ============ STAT CARDS ============ */
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: all 0.4s var(--transition-smooth);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
        opacity: 0;
        transition: all 0.4s var(--transition-smooth);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.12);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        transition: all 0.4s var(--transition-spring);
        transform-origin: center;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.2) rotate(5deg);
    }

    /* ============ DETAIL ROW EFFECTS ============ */
    .detail-row {
        position: relative;
        transition: all 0.3s var(--transition-smooth);
        overflow: hidden;
    }

    .detail-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, rgba(59, 130, 246, 0), rgba(59, 130, 246, 0.4), rgba(59, 130, 246, 0));
        opacity: 0;
        transition: opacity 0.3s var(--transition-smooth);
    }

    .detail-row:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.04), transparent);
        padding-left: 12px;
        margin-left: -8px;
    }

    .detail-row:hover::before {
        opacity: 1;
    }

    /* ============ CASE INFO CARDS ============ */
    .info-card {
        position: relative;
        overflow: hidden;
        transition: all 0.4s var(--transition-smooth);
    }

    .info-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        opacity: 0;
        background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.1));
        transition: opacity 0.4s var(--transition-smooth);
        pointer-events: none;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px currentColor/10;
    }

    .info-card:hover::before {
        opacity: 1;
    }

    /* ============ ITEMS LIST ============ */
    .item-row {
        position: relative;
        transition: all 0.35s var(--transition-smooth);
        overflow: hidden;
    }

    .item-row::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        transition: all 0.35s var(--transition-smooth);
        border-radius: 0 8px 8px 0;
    }

    .item-row:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.08), rgba(59, 130, 246, 0.02));
        transform: translateX(4px);
    }

    /* ============ TIMELINE EFFECTS ============ */
    .timeline-node {
        position: relative;
        transition: all 0.4s var(--transition-smooth);
    }

    .timeline-node::before {
        content: '';
        position: absolute;
        inset: -12px;
        border-radius: 50%;
        background: radial-gradient(circle, currentColor/20, transparent);
        opacity: 0;
        transition: opacity 0.4s var(--transition-smooth);
        z-index: -1;
    }

    .timeline-node:hover::before {
        opacity: 1;
    }

    .timeline-dot {
        transition: all 0.4s var(--transition-spring);
        animation: glow 2s ease-in-out infinite;
    }

    /* ============ MODAL EFFECTS ============ */
    .modal-backdrop {
        transition: opacity 0.35s var(--transition-smooth);
        animation: fadeInUp 0.4s var(--transition-smooth);
    }

    .modal-panel {
        transition: all 0.4s var(--transition-spring);
    }

    .modal-panel.modal-hidden-state {
        opacity: 0;
        transform: scale(0.92) translateY(20px);
    }

    .modal-backdrop.hidden + * {
        pointer-events: none;
    }

    /* ============ STATUS CHIPS ============ */
    .status-chip {
        position: relative;
        transition: all 0.35s var(--transition-smooth);
        overflow: hidden;
    }

    .status-chip::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), rgba(255, 255, 255, 0.3), transparent 50%);
        opacity: 0;
        transition: opacity 0.4s var(--transition-smooth);
        pointer-events: none;
    }

    .status-chip:hover::before {
        opacity: 0.5;
    }

    .status-chip:active {
        transform: scale(0.98);
    }

    /* ============ INPUT & TEXTAREA ============ */
    .input-interactive {
        position: relative;
        transition: all 0.3s var(--transition-smooth);
    }

    .input-interactive::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0;
        transform: scaleX(0);
        transition: all 0.4s var(--transition-spring);
    }

    .input-interactive:focus::after {
        opacity: 1;
        transform: scaleX(1);
    }

    .input-interactive:focus {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
    }

    /* ============ ICON ANIMATIONS ============ */
    .icon-pop {
        transition: transform 0.3s var(--transition-spring);
    }

    .icon-pop:hover {
        transform: scale(1.25) rotate(-5deg);
    }

    /* ============ TOAST ============ */
    #toast-stack > div {
        animation: toastIn 0.4s var(--transition-smooth) both;
    }

    /* ============ SPINNER ============ */
    .spinner-ring {
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-top-color: #fff;
        border-radius: 9999px;
        animation: spin 0.7s linear infinite;
    }

    /* ============ STATUS DOT ============ */
    .status-dot {
        position: relative;
    }

    .status-dot::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 9999px;
        border: 2px solid currentColor;
        opacity: 0;
        animation: ringPop 1.8s ease-out infinite;
    }

    .idle-critical {
        animation: pulseSoft 2.2s ease-in-out infinite;
    }

    /* ============ PROGRESS BAR ============ */
    .progress-fill-animated {
        background: linear-gradient(90deg, #3b82f6, #6366f1 40%, #38bdf8, #0ea5e9);
        background-size: 300% 100%;
        background-position: 0%;
        transition: width 0.8s var(--transition-smooth);
        animation: gradientShift 8s ease-in-out infinite;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
        position: relative;
    }

    .progress-fill-animated::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        border-radius: inherit;
        animation: stripe 2s linear infinite;
    }

    /* ============ MICRO INTERACTIONS ============ */
    .hover-lift {
        transition: all 0.4s var(--transition-smooth);
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.15);
    }

    /* ============ GRADIENT BACKGROUNDS ============ */
    .gradient-flow {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
        background-size: 400% 400%;
        animation: gradientShift 12s ease infinite;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 768px) {
        .card-interactive:hover {
            transform: translateY(-4px);
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .item-row:hover {
            transform: translateX(2px);
        }
    }

    /* ============ GLASS MORPHISM ============ */
    .glass-effect {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    /* ============ CONTAINER ANIMATIONS ============ */
    .container-main {
        perspective: 1000px;
    }

    .fade-entrance {
        opacity: 0;
        animation: fadeInUp 0.6s var(--transition-smooth) forwards;
    }

    .slide-in-left {
        animation: slideInLeft 0.5s var(--transition-smooth);
    }

    .slide-in-right {
        animation: slideInRight 0.5s var(--transition-smooth);
    }
</style>
@endpush

<div class="container-main max-w-5xl mx-auto space-y-5 p-4 md:p-0">

    {{-- Toast Container --}}
    <div id="toast-stack" class="fixed top-5 right-5 z-[100] flex flex-col gap-3 w-80 max-w-[calc(100vw-2.5rem)]"></div>

    {{-- Breadcrumb + Actions --}}
    <div class="flex items-center justify-between flex-wrap gap-4 animate-in">
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('garansi.index') }}" class="hover:text-blue-600 transition-colors duration-300">Data Garansi</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-slate-600 font-medium">{{ $garansi->nama }}</span>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('garansi.edit', $garansi) }}" class="btn-interactive group bg-white border border-slate-200 hover:border-blue-300 hover:bg-blue-50 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm hover:shadow-md transition-all duration-300">
                <i class="fas fa-edit group-hover:scale-110 transition-transform duration-300"></i> Edit
            </a>
            <a href="{{ route('garansi.index') }}" class="btn-interactive group bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition-all duration-300">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform duration-300"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Hero: Ringkasan --}}
    <div class="hero-card card-interactive animate-in stagger-1 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500 bg-[length:200%_100%] animate-[stripe_3s_linear_infinite]"></div>
        <div class="p-6 md:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                {{-- Left Section --}}
                <div class="flex items-center gap-4 min-w-0 slide-in-left">
                    <div class="avatar-interactive w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shrink-0 shadow-lg shadow-blue-600/30 ring-4 ring-blue-50 transition-all duration-400">
                        {{ $initials ?: '?' }}
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl font-bold text-slate-900 truncate">{{ $garansi->nama }}</h1>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="font-mono text-xs text-slate-500 bg-slate-100 rounded-lg px-2.5 py-1 hover:bg-slate-200 transition-colors duration-300 cursor-default">
                                {{ $garansi->invoice_pembelian ?? 'Tanpa invoice' }}
                            </span>
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $idleBadge }} font-medium status-dot {{ $belumSelesai && $idleDays >= 2 ? 'idle-critical' : '' }} transition-all duration-300">
                                @if($belumSelesai) {{ $idleDays }} hari diam @else Selesai @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Progress Section --}}
                <div class="lg:w-72 shrink-0 slide-in-right">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-sm font-semibold {{ $garansi->status_color }} shadow-sm hover:shadow-md transition-all duration-300">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            {{ $garansi->status_label }}
                        </span>
                        <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded-lg">{{ $garansi->status_progress }}%</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden shadow-inner ring-1 ring-slate-200/50">
                        <div class="h-full rounded-full progress-fill-animated" style="width:{{ $garansi->status_progress }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Update Status toolbar --}}
            <div class="mt-7 pt-6 border-t border-slate-100">
                <button id="btn-open-status-modal" class="btn-interactive w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-4 py-3.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 transition-all duration-300 group">
                    <i class="fas fa-sync-alt icon-pop"></i> 
                    <span class="group-hover:tracking-wider transition-all duration-300">Update Status Garansi</span>
                </button>

                {{-- Info Resi & SN jika ada --}}
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    @if($garansi->sn_pengganti)
                    <div class="info-card bg-purple-50 text-purple-700 rounded-xl px-4 py-3 border border-purple-100/70 hover:border-purple-200">
                        <i class="fas fa-microchip mr-2"></i> 
                        <span class="font-semibold">SN Pengganti:</span> 
                        <span class="font-mono font-bold text-purple-800">{{ $garansi->sn_pengganti }}</span>
                    </div>
                    @endif
                    @if($garansi->resi_pengiriman)
                    <div class="info-card bg-indigo-50 text-indigo-700 rounded-xl px-4 py-3 border border-indigo-100/70 hover:border-indigo-200">
                        <i class="fas fa-truck mr-2"></i> 
                        <span class="font-semibold">Resi:</span> 
                        <span class="font-mono font-bold text-indigo-800">{{ $garansi->resi_pengiriman }}</span>
                    </div>
                    @endif
                </div>

                @if($garansi->catatan)
                <div class="mt-4 flex items-start gap-3 text-sm text-slate-600 bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-xl px-4 py-3.5 border border-slate-100 hover:border-slate-200 transition-all duration-300">
                    <i class="fas fa-note-sticky text-slate-400 mt-0.5 shrink-0"></i>
                    <span><span class="font-medium text-slate-800">Catatan:</span> {{ $garansi->catatan }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card card-interactive animate-in stagger-1 bg-white rounded-2xl border border-slate-200 p-5 hover:border-blue-200">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 stat-icon shadow-sm">
                <i class="fas fa-box text-sm"></i>
            </div>
            <div class="mt-3">
                <div class="text-xs text-slate-400 font-medium">Barang</div>
                <div class="font-bold text-slate-900 text-lg">{{ $garansi->items->count() }}</div>
            </div>
        </div>

        <div class="stat-card card-interactive animate-in stagger-2 bg-white rounded-2xl border border-slate-200 p-5 hover:border-emerald-200">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 stat-icon shadow-sm">
                <i class="fab fa-whatsapp text-sm"></i>
            </div>
            <div class="mt-3">
                <div class="text-xs text-slate-400 font-medium">WA Terkirim</div>
                <div class="font-bold text-slate-900 text-lg">{{ $waTerkirim }}</div>
            </div>
        </div>

        <div class="stat-card card-interactive animate-in stagger-3 bg-white rounded-2xl border border-slate-200 p-5 hover:border-violet-200">
            <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 stat-icon shadow-sm">
                <i class="fas fa-calendar-check text-sm"></i>
            </div>
            <div class="mt-3">
                <div class="text-xs text-slate-400 font-medium">Tgl Sampai</div>
                <div class="font-bold text-slate-900 text-sm">{{ optional($garansi->tanggal_sampai)->format('d/m/Y') ?? '-' }}</div>
            </div>
        </div>

        <div class="stat-card card-interactive animate-in stagger-4 bg-white rounded-2xl border border-slate-200 p-5 hover:border-amber-200">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 stat-icon shadow-sm">
                <i class="fas fa-shop text-sm"></i>
            </div>
            <div class="mt-3">
                <div class="text-xs text-slate-400 font-medium">Marketplace</div>
                <div class="font-bold text-slate-900 text-sm truncate">{{ $garansi->nama_marketplace ?: '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Customer Data --}}
        <div class="card-interactive animate-in stagger-2 bg-white rounded-2xl border border-slate-200 p-6 hover:border-blue-200">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-user text-xs"></i>
                </span>
                Data Customer
            </h3>
            <div class="divide-y divide-slate-100">
                <div class="detail-row flex items-center justify-between gap-3 px-3 py-3 rounded-lg transition-all duration-300">
                    <div class="flex items-center gap-2.5 text-slate-400 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-500 flex items-center justify-center shrink-0 transition-colors duration-300"><i class="fas fa-id-badge text-[10px]"></i></span>
                        Nama
                    </div>
                    <div class="font-semibold text-slate-900 text-sm truncate">{{ $garansi->nama }}</div>
                </div>

                <div class="detail-row flex items-center justify-between gap-3 px-3 py-3 rounded-lg transition-all duration-300 group">
                    <div class="flex items-center gap-2.5 text-slate-400 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-500 flex items-center justify-center shrink-0 transition-colors duration-300"><i class="fas fa-phone text-[10px]"></i></span>
                        No HP
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold text-slate-900 text-sm">{{ $garansi->no_hp }}</span>
                        <button type="button" class="copy-btn text-slate-300 hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100 duration-300" data-copy="{{ $garansi->no_hp }}" title="Salin nomor">
                            <i class="fas fa-copy text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="detail-row flex items-center justify-between gap-3 px-3 py-3 rounded-lg transition-all duration-300">
                    <div class="flex items-center gap-2.5 text-slate-400 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 transition-colors duration-300"><i class="fas fa-receipt text-[10px]"></i></span>
                        Invoice Pembelian
                    </div>
                    <div class="font-semibold text-slate-900 text-xs font-mono truncate">{{ $garansi->invoice_pembelian ?? '-' }}</div>
                </div>

                <div class="detail-row flex items-center justify-between gap-3 px-3 py-3 rounded-lg transition-all duration-300">
                    <div class="flex items-center gap-2.5 text-slate-400 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 transition-colors duration-300"><i class="fas fa-shop text-[10px]"></i></span>
                        Marketplace
                    </div>
                    <div class="font-semibold text-slate-900 text-sm truncate">{{ $garansi->nama_marketplace ?: '-' }}</div>
                </div>

                <div class="detail-row flex items-center justify-between gap-3 px-3 py-3 rounded-lg transition-all duration-300">
                    <div class="flex items-center gap-2.5 text-slate-400 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 transition-colors duration-300"><i class="fas fa-cart-shopping text-[10px]"></i></span>
                        Tanggal Beli
                    </div>
                    <div class="font-semibold text-slate-900 text-sm">{{ optional($garansi->tanggal_beli)->format('d/m/Y') ?? '-' }}</div>
                </div>

                <div class="detail-row flex items-center justify-between gap-3 px-3 py-3 rounded-lg transition-all duration-300">
                    <div class="flex items-center gap-2.5 text-slate-400 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 transition-colors duration-300"><i class="fas fa-hourglass-end text-[10px]"></i></span>
                        Tanggal Sampai
                    </div>
                    <div class="font-semibold text-slate-900 text-sm">{{ optional($garansi->tanggal_sampai)->format('d/m/Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Case Details --}}
        <div class="card-interactive animate-in stagger-3 bg-white rounded-2xl border border-slate-200 p-6 hover:border-emerald-200">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-clipboard-list text-xs"></i>
                </span>
                Detail Kasus
            </h3>

            {{-- WhatsApp Location --}}
            <div class="info-card relative bg-gradient-to-br from-emerald-50 to-emerald-50/50 border border-emerald-100 rounded-2xl p-4 mb-3 hover:border-emerald-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/25 icon-pop">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] uppercase tracking-wider text-emerald-600/75 font-bold">Lokasi Chat WhatsApp</div>
                        <div class="font-bold text-slate-900 truncate">{{ ucfirst($garansi->lokasi_chat) }}</div>
                        <div class="text-xs text-slate-500 font-mono">{{ config("whatsapp.lokasi.{$garansi->lokasi_chat}.nomor") }}</div>
                    </div>
                </div>
            </div>

            {{-- Damage --}}
            <div class="info-card relative bg-gradient-to-br from-amber-50 to-amber-50/50 border border-amber-100 rounded-2xl p-4 mb-3 hover:border-amber-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0"><i class="fas fa-screwdriver-wrench text-[10px]"></i></span>
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wide">Kerusakan</span>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $garansi->kerusakan }}</p>
            </div>

            {{-- Completeness --}}
            <div class="info-card relative bg-gradient-to-br from-violet-50 to-violet-50/50 border border-violet-100 rounded-2xl p-4 hover:border-violet-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-7 h-7 rounded-lg bg-violet-500 text-white flex items-center justify-center shrink-0"><i class="fas fa-boxes-stacked text-[10px]"></i></span>
                    <span class="text-[10px] font-bold text-violet-700 uppercase tracking-wide">Kelengkapan Barang</span>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $garansi->kelengkapan_barang }}</p>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="card-interactive animate-in stagger-3 bg-white rounded-2xl border border-slate-200 p-6 hover:border-blue-200">
        <h3 class="font-semibold text-slate-900 mb-5 flex items-center gap-2">
            <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-laptop text-xs"></i>
            </span>
            Daftar Barang 
            <span class="text-slate-400 font-normal text-sm">({{ $garansi->items->count() }})</span>
        </h3>

        <div class="space-y-3">
            @foreach($garansi->items as $index => $item)
            <div data-item-row="{{ $item->id }}" class="item-row relative overflow-hidden rounded-2xl border border-slate-200 hover:border-blue-300 p-4 pl-5">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b {{ $item->is_replaced ? 'from-purple-500 to-purple-300' : 'from-blue-500 to-sky-300' }} rounded-r"></div>
                
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex items-center gap-3 md:w-64 shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-50 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0 ring-1 ring-slate-100 shadow-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="font-semibold text-slate-900 truncate">{{ $item->nama_barang }}</div>
                                @if($item->is_replaced)
                                <span class="shrink-0 text-[8px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">Diganti</span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-400 font-mono truncate">SN: {{ $item->serial_number }}</div>
                        </div>
                    </div>

                    <div class="flex-1 md:border-l md:border-slate-100 md:pl-4">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-2 flex items-center gap-1">
                            <i class="fas fa-shuffle text-[9px]"></i> SN Baru (Replace)
                        </div>
                        
                        <div class="sn-baru-display {{ $item->is_replaced ? '' : 'hidden' }} flex items-center gap-2 flex-wrap animate-in-fast">
                            <span class="font-mono text-xs font-medium text-purple-700 bg-purple-50 border border-purple-100 rounded-lg px-2.5 py-1 hover:bg-purple-100 transition-all duration-300 sn-baru-text">{{ $item->serial_number_baru }}</span>
                            <span class="text-xs text-slate-400 sn-baru-tanggal">{{ $item->replaced_at?->format('d/m/Y H:i') }}</span>
                            <button type="button" class="btn-edit-sn text-xs text-blue-600 hover:text-blue-800 transition-colors duration-300 font-medium" data-item-id="{{ $item->id }}">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>

                        <div class="sn-baru-form {{ $item->is_replaced ? 'hidden' : '' }} flex items-center gap-2">
                            <input type="text" class="input-interactive sn-baru-input border-2 border-slate-200 rounded-lg px-3 py-2 text-xs font-mono w-full max-w-[200px] outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                                placeholder="Input SN baru..." value="{{ $item->serial_number_baru }}">
                            <button type="button" class="btn-save-sn btn-interactive bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-xs font-medium shrink-0 shadow-sm hover:shadow-md transition-all duration-300"
                                data-url="{{ route('garansi.items.replace-sn', [$garansi->id, $item->id]) }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- WhatsApp Logs --}}
    <div class="card-interactive animate-in stagger-4 bg-white rounded-2xl border border-slate-200 p-6 hover:border-emerald-200">
        <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
            <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fab fa-whatsapp text-xs"></i>
                </span>
                Riwayat WhatsApp
                @if($garansi->whatsappLogs->isNotEmpty())
                <span class="text-xs font-normal text-slate-400 ml-1">({{ $garansi->whatsappLogs->count() }})</span>
                @endif
            </h3>
            <button id="btn-send-wa" class="btn-interactive group bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm hover:shadow-md transition-all duration-300">
                <i class="fas fa-paper-plane group-hover:scale-110 transition-transform duration-300"></i> Kirim WA Manual
            </button>
        </div>

        {{-- WA Form --}}
        <div id="wa-form" class="hidden mb-5 bg-gradient-to-br from-emerald-50 to-slate-50 border-2 border-emerald-100 rounded-2xl p-5 animate-in-fast">
            <div class="flex items-center gap-2 mb-3 text-xs font-bold text-emerald-700 uppercase tracking-wide">
                <i class="fas fa-pen-to-square"></i> Tulis Pesan Manual
            </div>
            <textarea id="wa-message" rows="4" placeholder="Tulis pesan WhatsApp..."
                class="input-interactive w-full border-2 border-emerald-100 bg-white rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 resize-none"></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="btn-cancel-wa" class="btn-interactive bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300">Batal</button>
                <button type="button" id="btn-send-wa-confirm" class="btn-interactive bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow-md flex items-center gap-2 transition-all duration-300">
                    <i class="fas fa-paper-plane text-xs"></i> Kirim
                </button>
            </div>
        </div>

        {{-- Timeline --}}
        @if($garansi->whatsappLogs->isNotEmpty())
        <div class="relative">
            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gradient-to-b from-emerald-300 via-slate-200 to-transparent"></div>
            <div class="space-y-4">
                @foreach($garansi->whatsappLogs as $log)
                @php
                    $nodeStyle = match($log->status_kirim) {
                        'terkirim' => ['fa-check', 'bg-emerald-500', 'emerald'],
                        'gagal' => ['fa-xmark', 'bg-rose-500', 'rose'],
                        default => ['fa-clock', 'bg-slate-300', 'slate'],
                    };
                    $statusBadge = match($log->status_kirim) {
                        'terkirim' => 'bg-emerald-50 text-emerald-700',
                        'gagal' => 'bg-rose-50 text-rose-700',
                        default => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <div class="timeline-node relative flex gap-4 pl-8">
                    <div class="timeline-dot relative z-10 w-9 h-9 rounded-full {{ $nodeStyle[1] }} text-white flex items-center justify-center shrink-0 ring-4 ring-white shadow-lg transition-all duration-400 hover:scale-125">
                        <i class="fas {{ $nodeStyle[0] }} text-xs"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0 bg-slate-50/60 hover:bg-white border border-slate-100 hover:border-slate-200 rounded-2xl p-4 transition-all duration-300">
                        <div class="flex items-center justify-between gap-3 mb-2 flex-wrap">
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <span class="font-bold text-slate-700">{{ strtoupper($log->tipe) }}</span>
                                <span class="text-slate-300">/</span>
                                <span>{{ ucfirst($log->lokasi) }}</span>
                                <span class="text-slate-300">/</span>
                                <span>{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $statusBadge }}">
                                    {{ ucfirst($log->status_kirim) }}
                                </span>
                            </div>
                            <button class="resend-wa-btn text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1.5 transition-all duration-300 hover:scale-105" data-url="{{ route('garansi.resend-wa', [$garansi->id, $log->id]) }}">
                                <i class="fas fa-rotate-right"></i> Kirim Ulang
                            </button>
                        </div>

                        @if($log->image_data)
                        <img src="{{ $log->image_data }}" class="card-interactive w-40 h-40 object-cover rounded-xl border border-slate-200 mb-3 cursor-pointer shadow-md ring-1 ring-slate-100/50" onclick="window.open('{{ $log->image_data }}', '_blank')">
                        @endif

                        <p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $log->pesan }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-300 flex items-center justify-center mx-auto mb-3 animate-bounce">
                <i class="fab fa-whatsapp text-3xl"></i>
            </div>
            <p class="text-sm text-slate-400 font-medium">Belum ada riwayat WhatsApp.</p>
        </div>
        @endif
    </div>
</div>

{{-- ===== MODAL UPDATE STATUS DINAMIS - DRAGGABLE ===== --}}
@php
    $statusMeta = [
        'diterima'        => ['icon' => 'fa-inbox',              'active' => 'bg-slate-800 border-slate-800 text-white shadow-slate-800/30',   'idle' => 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'],
        'diperiksa'       => ['icon' => 'fa-magnifying-glass',   'active' => 'bg-blue-600 border-blue-600 text-white shadow-blue-600/30',       'idle' => 'border-slate-200 text-slate-600 hover:border-blue-300 hover:bg-blue-50'],
        'repair'          => ['icon' => 'fa-screwdriver-wrench', 'active' => 'bg-amber-500 border-amber-500 text-white shadow-amber-500/30',    'idle' => 'border-slate-200 text-slate-600 hover:border-amber-300 hover:bg-amber-50'],
        'replace'         => ['icon' => 'fa-shuffle',            'active' => 'bg-purple-600 border-purple-600 text-white shadow-purple-600/30', 'idle' => 'border-slate-200 text-slate-600 hover:border-purple-300 hover:bg-purple-50'],
        'to distribution' => ['icon' => 'fa-warehouse',          'active' => 'bg-indigo-600 border-indigo-600 text-white shadow-indigo-600/30', 'idle' => 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-indigo-50'],
        'pengiriman'      => ['icon' => 'fa-truck-fast',         'active' => 'bg-sky-500 border-sky-500 text-white shadow-sky-500/30',          'idle' => 'border-slate-200 text-slate-600 hover:border-sky-300 hover:bg-sky-50'],
        'selesai'         => ['icon' => 'fa-circle-check',       'active' => 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-600/30', 'idle' => 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50'],
    ];
    $defaultMeta = ['icon' => 'fa-circle-dot', 'active' => 'bg-blue-600 border-blue-600 text-white shadow-blue-600/30', 'idle' => 'border-slate-200 text-slate-600 hover:border-blue-300 hover:bg-blue-50'];
@endphp
<div id="modal-status-dynamic" class="modal-backdrop fixed top-0 left-0 right-0 bottom-0 z-50 hidden flex items-center justify-center p-4 opacity-0 pointer-events-none" style="transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);">
    <div id="modal-status-panel" class="modal-panel modal-hidden-state pointer-events-auto bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col cursor-grab active:cursor-grabbing" style="touch-action: none;">

        {{-- Header --}}
        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-500 px-7 pt-7 pb-8 shrink-0 overflow-hidden modal-header cursor-grab active:cursor-grabbing" style="user-select: none;">
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/15"></div>
            <div class="absolute -bottom-14 -left-6 w-32 h-32 rounded-full bg-white/10"></div>
            <div class="relative flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 ring-2 ring-white/40 flex items-center justify-center shrink-0 animate-bounce">
                        <i class="fas fa-sync-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Update Status Garansi</h3>
                        <p class="text-blue-100 text-xs mt-1">Perbarui progres &amp; kirim notifikasi WhatsApp</p>
                    </div>
                </div>
                <button type="button" class="modal-close w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center hover:rotate-90 transition-all duration-300 shrink-0">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        <form id="form-update-status-dynamic" class="px-7 pb-7 pt-6 overflow-y-auto flex-1">
            @csrf

            <div class="mb-6">
                <label class="text-xs uppercase tracking-wide text-slate-500 font-bold block mb-3">Pilih Status Baru</label>
                <select id="status-select-dynamic" name="status" class="hidden">
                    @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ $garansi->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <div id="status-chip-group" class="grid grid-cols-2 gap-3">
                    @foreach($statusList as $status)
                    @php $meta = $statusMeta[strtolower($status)] ?? $defaultMeta; @endphp
                    <button type="button"
                        class="status-chip btn-interactive flex items-center gap-2.5 rounded-xl border-2 px-3.5 py-3 text-sm font-semibold text-left shadow-sm transition-all duration-300 {{ $garansi->status === $status ? $meta['active'] : $meta['idle'] }}"
                        data-value="{{ $status }}" data-active-class="{{ $meta['active'] }}" data-idle-class="{{ $meta['idle'] }}">
                        <i class="fas {{ $meta['icon'] }} text-base w-4 text-center"></i>
                        <span class="truncate">{{ ucfirst($status) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Conditional Fields --}}
            <div id="field-replace" class="mb-5 hidden animate-in-fast">
                <label class="text-xs uppercase tracking-wide text-purple-600 font-bold flex items-center gap-1.5 mb-2"><i class="fas fa-microchip"></i> Serial Number Pengganti *</label>
                <input type="text" name="sn_pengganti" class="input-interactive w-full border-2 border-purple-200 bg-purple-50/60 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-mono text-sm transition-all duration-300" placeholder="Masukkan SN barang baru...">
            </div>

            <div id="field-camera" class="mb-5 hidden animate-in-fast">
                <label class="text-xs uppercase tracking-wide text-slate-500 font-bold flex items-center gap-1.5 mb-3"><i class="fas fa-camera"></i> Bukti Foto (Opsional)</label>
                <div id="camera-card" class="border-2 border-dashed border-slate-300 bg-slate-50/80 rounded-2xl p-6 text-center transition-all duration-300 hover:border-blue-400 hover:bg-blue-50/40">
                    <video id="camera-stream" class="w-full rounded-xl hidden shadow-lg"></video>
                    <canvas id="camera-canvas" class="hidden"></canvas>
                    <img id="photo-preview" class="card-interactive w-full rounded-xl hidden mb-3 animate-in-fast shadow-lg ring-2 ring-slate-200" src="">

                    <input type="hidden" name="bukti_foto_data" id="bukti-foto-data">
                    <input type="file" id="bukti-foto-file" class="hidden" accept="image/*" capture="environment">

                    <div id="camera-buttons">
                        <div id="camera-placeholder" class="mb-3">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-sm ring-2 ring-slate-100 flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-camera-retro text-slate-400 text-xl"></i>
                            </div>
                            <p class="text-xs text-slate-500 font-medium">Ambil foto sebagai bukti kondisi barang</p>
                        </div>
                        <div class="flex flex-wrap justify-center gap-2">
                            <button type="button" id="btn-start-camera" class="btn-interactive bg-white border-2 border-slate-300 hover:border-blue-400 hover:text-blue-600 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all duration-300">
                                <i class="fas fa-video mr-2"></i> Buka Kamera
                            </button>
                            <button type="button" id="btn-upload-photo" class="btn-interactive bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow-md transition-all duration-300">
                                <i class="fas fa-camera mr-2"></i> Pilih Foto
                            </button>
                            <button type="button" id="btn-take-photo" class="btn-interactive bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hidden shadow-sm hover:shadow-md transition-all duration-300">
                                <i class="fas fa-circle mr-2"></i> Ambil Foto
                            </button>
                            <button type="button" id="btn-retake-photo" class="btn-interactive bg-white border-2 border-slate-300 hover:border-blue-400 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-semibold hidden shadow-sm transition-all duration-300">
                                <i class="fas fa-rotate-right mr-2"></i> Ambil Ulang
                            </button>
                        </div>
                    </div>
                    <p id="camera-status" class="text-xs text-slate-400 mt-3"></p>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-xs uppercase tracking-wide text-slate-500 font-bold flex items-center gap-1.5 mb-2"><i class="fas fa-note-sticky"></i> Catatan Internal</label>
                <textarea name="catatan" rows="3" placeholder="Catatan teknisi (opsional)..." class="input-interactive w-full border-2 border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none transition-all duration-300">{{ $garansi->catatan }}</textarea>
            </div>

            <div class="flex gap-3 mt-7 pt-5 border-t border-slate-200">
                <button type="button" class="modal-close btn-interactive flex-1 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 font-semibold text-slate-600 transition-all duration-300">Batal</button>
                <button type="submit" class="btn-submit-status btn-interactive flex-1 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold shadow-lg shadow-blue-600/25 hover:shadow-xl hover:shadow-blue-600/35 flex items-center justify-center gap-2 transition-all duration-300">
                    <i class="fas fa-check"></i>
                    <span class="btn-submit-label">Simpan Status</span>
                </button>
            </div>
        </form>
    </div>
</div>
@include('bublechat')
@endsection

@push('scripts')
<script>
    (() => {
        const $ = (selector, scope = document) => scope.querySelector(selector);
        const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
        const csrf = $('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

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
                button.innerHTML = `<span class="spinner-ring inline-block w-3.5 h-3.5"></span><span>${loadingText}</span>`;
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
                success: { bg: 'bg-emerald-600', icon: 'fa-circle-check' },
                error: { bg: 'bg-rose-600', icon: 'fa-circle-exclamation' },
                info: { bg: 'bg-blue-600', icon: 'fa-circle-info' },
            }[type] || { bg: 'bg-slate-800', icon: 'fa-circle-info' };

            const toast = document.createElement('div');
            toast.className = `${palette.bg} text-white rounded-xl shadow-xl shadow-black/20 px-5 py-4 flex items-start gap-3 text-sm font-medium backdrop-blur-sm`;
            toast.innerHTML = `<i class="fas ${palette.icon} mt-0.5 shrink-0"></i><span class="flex-1"></span>`;
            toast.querySelector('span').textContent = message;
            stack.appendChild(toast);

            setTimeout(() => {
                toast.style.transition = 'opacity .35s ease, transform .35s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(30px) scale(0.9)';
                setTimeout(() => toast.remove(), 350);
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

        $$('.copy-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const text = this.dataset.copy || '';
                const icon = $('i', this);

                try {
                    await copyText(text);
                    showToast(`Nomor disalin: ${text}`, 'info');
                    icon?.classList.replace('fa-copy', 'fa-check');
                    setTimeout(() => icon?.classList.replace('fa-check', 'fa-copy'), 1500);
                } catch {
                    showToast('Gagal menyalin nomor.', 'error');
                }
            });
        });

        $$('.btn-save-sn').forEach(button => {
            button.addEventListener('click', async function() {
                const row = this.closest('[data-item-row]');
                const input = $('.sn-baru-input', row);
                const snBaru = input?.value.trim();

                if (!snBaru) {
                    showToast('SN baru tidak boleh kosong.', 'error');
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

        $$('.btn-edit-sn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('[data-item-row]');
                $('.sn-baru-display', row)?.classList.add('hidden');
                $('.sn-baru-form', row)?.classList.remove('hidden');
                $('.sn-baru-input', row)?.focus();
            });
        });

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
                chip.className = `status-chip btn-interactive flex items-center gap-2.5 rounded-xl border-2 px-3.5 py-3 text-sm font-semibold text-left shadow-sm transition-all duration-300 ${isActive ? chip.dataset.activeClass : chip.dataset.idleClass}`;
            });
        }

        function toggleField(field, isVisible) {
            field?.classList.toggle('hidden', !isVisible);
        }

        function toggleStatusFields() {
            const status = selectedStatus();
            
            // Status Replace: butuh SN
            const needsSn = status === 'replace';
            
            // Status yang butuh camera: pengiriman, repair, to distribution
            const needsCamera = ['repair', 'to distribution', 'pengiriman', 'selesai'].includes(status);

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
            setTimeout(() => {
                modalStatus.classList.add('hidden');
                modalStatus.classList.remove('flex');
            }, 220);
            stopCamera();
        }

        $('#btn-open-status-modal')?.addEventListener('click', openModal);
        $$('.modal-close').forEach(btn => btn.addEventListener('click', closeModal));

        selectStatus?.addEventListener('change', () => {
            syncChipHighlight();
            toggleStatusFields();
        });

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
                setCameraStatus('Browser ini tidak mendukung kamera langsung. Gunakan tombol Pilih Foto.');
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
                setCameraStatus('Kamera aktif. Arahkan ke barang.');
            } catch (err) {
                photoFile?.click();
                showToast('Kamera langsung tidak bisa dibuka. Silakan pilih atau ambil foto dari perangkat.', 'info');
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
                setCameraStatus('Foto dipilih. Klik Simpan Status untuk menyimpan.');
            };
            reader.onerror = () => showToast('Gagal membaca foto.', 'error');
            reader.readAsDataURL(file);
        });

        btnTakePhoto?.addEventListener('click', () => {
            if (!video?.videoWidth || !canvas) {
                showToast('Kamera belum siap. Coba ulangi sebentar lagi.', 'error');
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
            setCameraStatus('Foto diambil. Klik Simpan Status untuk menyimpan.');
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

                showToast('Status berhasil diperbarui dan WhatsApp dikirim!', 'success');
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                showToast(err.message || 'Gagal menyimpan status.', 'error');
            } finally {
                setLoading(btn, false);
            }
        });

        // ============ MODAL DRAGGABLE ============
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
            if (e.button !== 0) return; // Only left mouse button
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

        // Attach drag listeners ke header modal
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