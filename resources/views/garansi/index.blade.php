@extends('layouts.app')
@section('title', 'Data Garansi')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Garansi</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola dan pantau seluruh klaim garansi</p>
        </div>
        <a href="{{ route('garansi.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i> Buat Garansi
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari (Nama / Invoice / No HP)</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, invoice, no HP, atau SN..."
                        class="w-full border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
            </div>
            <div class="flex-1 min-w-[170px]">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Filter Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    <option value="">Semua Status</option>
                    @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium">
                    <i class="fas fa-filter mr-1.5"></i> Filter
                </button>
                <a href="{{ route('garansi.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Keterangan Warna --}}
    <div class="flex items-center gap-4 text-xs text-slate-500 px-1">
        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Diam 1 hari, belum selesai</span>
        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Diam ≥ 2 hari, belum selesai</span>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">Nama</th>
                        <th class="text-left px-4 py-3 font-medium">Invoice</th>
                        <th class="text-left px-4 py-3 font-medium">No HP</th>
                        <th class="text-left px-4 py-3 font-medium">Barang</th>
                        <th class="text-left px-4 py-3 font-medium">Lokasi Chat</th>
                        <th class="text-left px-4 py-3 font-medium">Status</th>
                        <th class="text-left px-4 py-3 font-medium">Tgl Sampai</th>
                        <th class="text-left px-4 py-3 font-medium">Terakhir Update</th>
                        <th class="text-right px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($garansis as $garansi)
                    @php
                        $idleDays = (int) $garansi->updated_at->startOfDay()->diffInDays(now()->startOfDay());
                        $belumSelesai = strtolower($garansi->status) !== 'selesai';

                        $accent = '';
                        $idleColor = 'text-slate-400';
                        if ($belumSelesai && $idleDays >= 2) {
                            $accent = 'border-l-4 border-l-rose-400';
                            $idleColor = 'text-rose-600 font-semibold';
                        } elseif ($belumSelesai && $idleDays >= 1) {
                            $accent = 'border-l-4 border-l-amber-400';
                            $idleColor = 'text-amber-600 font-semibold';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors {{ $accent }}">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $garansi->nama }}</td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $garansi->invoice_pembelian ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $garansi->no_hp }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            @if($garansi->items->count() > 1)
                                <span class="text-xs">{{ $garansi->items->first()->nama_barang }} <span class="text-slate-400">+{{ $garansi->items->count() - 1 }}</span></span>
                            @else
                                <span class="text-xs">{{ $garansi->items->first()->nama_barang ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-600">
                                {{ ucfirst($garansi->lokasi_chat) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $garansi->status_color }}">
                                {{ ucfirst($garansi->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">{{ $garansi->tanggal_sampai->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600 text-xs">
                            {{ $garansi->updated_at->format('d/m/Y H:i') }}
                            <span class="block mt-0.5 text-[10px] {{ $idleColor }}">
                                {{ $idleDays }} hari lalu
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('garansi.show', $garansi) }}" class="text-slate-400 hover:text-blue-600 transition-colors" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('garansi.edit', $garansi) }}" class="text-slate-400 hover:text-emerald-600 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->user()->role === 'admin')
                                <form action="{{ route('garansi.destroy', $garansi) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="inline">
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
                        <td colspan="9" class="px-4 py-16 text-center text-slate-400">
                            <i class="fas fa-folder-open text-3xl mb-3 block"></i>
                            Tidak ada data ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $garansis->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection