@extends('layouts.app')
@section('title', 'Data Garansi')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Data Garansi</h1>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-500 mb-1">Cari (Nama / Invoice / No HP)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs text-gray-500 mb-1">Filter Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Semua Status</option>
                    @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('garansi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Keterangan Warna --}}
    <div class="flex items-center gap-4 text-xs text-gray-500 px-1">
        <span class="flex items-center gap-1"><span class="w-3 h-3 bg-amber-100 border border-amber-200 rounded"></span> Diam 1 hari</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-200 border border-red-300 rounded"></span> Diam >= 2 hari</span>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">Nama</th>
                        <th class="text-left px-4 py-3 font-medium">Invoice</th>
                        <th class="text-left px-4 py-3 font-medium">No HP</th>
                        <th class="text-left px-4 py-3 font-medium">Barang</th>
                        <th class="text-left px-4 py-3 font-medium">Lokasi Chat</th>
                        <th class="text-left px-4 py-3 font-medium">Status</th>
                        <th class="text-left px-4 py-3 font-medium">Tgl Sampai</th>
                        <th class="text-left px-4 py-3 font-medium">Terakhir Update</th>
                        <th class="text-left px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($garansis as $garansi)
                    @php
                        // PERBAIKAN: Hitung selisih hari dari mulai hari ini ke hari update (angka bulat positif)
                        $idleDays = (int) $garansi->updated_at->startOfDay()->diffInDays(now()->startOfDay());
                        
                        // Menentukan class warna baris
                        $rowClass = 'hover:bg-gray-100'; // Warna default
                        
                        // Pastikan status disamakan ke huruf kecil (lowercase) untuk pengecekan
                        if (strtolower($garansi->status) !== 'selesai') {
                            if ($idleDays >= 2) {
                                $rowClass = 'bg-red-200 hover:bg-red-300';
                            } elseif ($idleDays >= 1) {
                                $rowClass = 'bg-amber-100 hover:bg-amber-200';
                            }
                        }
                    @endphp
                    <tr class="{{ $rowClass }} transition-colors duration-150">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $garansi->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $garansi->invoice_pembelian ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $garansi->no_hp }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($garansi->items->count() > 1)
                                <span class="text-xs">{{ $garansi->items->first()->nama_barang }} +{{ $garansi->items->count() - 1 }}</span>
                            @else
                                <span class="text-xs">{{ $garansi->items->first()->nama_barang ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                {{ ucfirst($garansi->lokasi_chat) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $garansi->status_color }}">
                                {{ ucfirst($garansi->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $garansi->tanggal_sampai->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $garansi->updated_at->format('d/m/Y H:i') }} <br>
                            <span class="text-[10px] font-bold {{ $idleDays >= 2 ? 'text-red-700' : ($idleDays >= 1 ? 'text-amber-700' : 'text-gray-400') }}">
                                ({{ $idleDays }} hari lalu)
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('garansi.show', $garansi) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('garansi.edit', $garansi) }}" class="text-green-600 hover:text-green-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('garansi.destroy', $garansi) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-400">Tidak ada data ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $garansis->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection