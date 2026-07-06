<x-app-layout>
    <div class="space-y-6">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-9 gap-3">
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
            
            {{-- KARTU SLA --}}
            <div class="bg-amber-50 rounded-xl shadow-sm p-4 border border-amber-200">
                <div class="text-xs text-amber-500 mb-1">SLA Warning (1-2 H)</div>
                <div class="text-2xl font-bold text-amber-600">{{ $slaWarning }}</div>
            </div>
            <div class="bg-red-50 rounded-xl shadow-sm p-4 border border-red-200">
                <div class="text-xs text-red-500 mb-1">SLA Breach (>2 H)</div>
                <div class="text-2xl font-bold text-red-600">{{ $slaBreach }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Garansi --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Data Garansi Terbaru</h2>
                    <a href="{{ route('garansi.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="text-left px-6 py-3 font-medium">Nama</th>
                                <th class="text-left px-6 py-3 font-medium">Invoice</th>
                                <th class="text-left px-6 py-3 font-medium">Lokasi</th>
                                <th class="text-left px-6 py-3 font-medium">Status</th>
                                <th class="text-left px-6 py-3 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentGaransis as $garansi)
                            @php
                                $idleDays = now()->diffInDays($garansi->updated_at);
                                $rowStyle = '';
                                if ($garansi->status !== 'selesai') {
                                    if ($idleDays >= 2) $rowStyle = 'background-color: #fecaca !important;';
                                    elseif ($idleDays >= 1) $rowStyle = 'background-color: #fef08a !important;';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50" style="{{ $rowStyle }}">
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $garansi->nama }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $garansi->invoice_pembelian ?? '-' }}</td>
                                <td class="px-6 py-3">{{ ucfirst($garansi->lokasi_chat) }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $garansi->status_color }}">
                                        {{ ucfirst($garansi->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('garansi.show', $garansi) }}" class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Audit Log --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900">Aktivitas Terbaru</h2>
                </div>
                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($activities as $activity)
                    <div class="text-sm border-l-2 border-blue-500 pl-3">
                        <p class="text-gray-800">
                            <span class="font-semibold">{{ $activity->causer->name ?? 'Sistem' }}</span>
                            <span class="text-gray-500">{{ $activity->description }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>