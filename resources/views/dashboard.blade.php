@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <a href="{{ route('garansi.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Data Garansi
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Total</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Pending</div>
            <div class="text-2xl font-bold text-gray-600">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Repair</div>
            <div class="text-2xl font-bold text-blue-600">{{ $stats['repair'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Replace</div>
            <div class="text-2xl font-bold text-purple-600">{{ $stats['replace'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Distribusi</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['distribusi'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Pengiriman</div>
            <div class="text-2xl font-bold text-indigo-600">{{ $stats['pengiriman'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Selesai</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['selesai'] }}</div>
        </div>
    </div>

    {{-- Recent Garansi --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Data Garansi Terbaru</h2>
            <a href="{{ route('garansi.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium">Nama</th>
                        <th class="text-left px-6 py-3 font-medium">SO Number</th>
                        <th class="text-left px-6 py-3 font-medium">No HP</th>
                        <th class="text-left px-6 py-3 font-medium">Lokasi</th>
                        <th class="text-left px-6 py-3 font-medium">Status</th>
                        <th class="text-left px-6 py-3 font-medium">Tanggal Sampai</th>
                        <th class="text-left px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentGaransis as $garansi)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $garansi->nama }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $garansi->so_number ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $garansi->no_hp }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                {{ ucfirst($garansi->lokasi_chat) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $garansi->status_color }}">
                                {{ ucfirst($garansi->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $garansi->tanggal_sampai->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('garansi.show', $garansi) }}" class="text-blue-600 hover:underline">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada data garansi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection