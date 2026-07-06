<x-app-layout>
    <div class="space-y-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Ringkasan status garansi &amp; aktivitas terkini.</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fas fa-calendar-day text-gray-400"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        {{-- Stats Cards --}}
        @php
            $statCards = [
                ['key' => 'total',      'label' => 'Total',      'value' => $stats['total'],      'icon' => 'fa-layer-group',   'color' => 'slate'],
                ['key' => 'pending',    'label' => 'Pending',    'value' => $stats['pending'],    'icon' => 'fa-hourglass-half','color' => 'gray'],
                ['key' => 'repair',     'label' => 'Repair',     'value' => $stats['repair'],     'icon' => 'fa-screwdriver-wrench', 'color' => 'blue'],
                ['key' => 'replace',    'label' => 'Replace',    'value' => $stats['replace'],    'icon' => 'fa-arrows-rotate', 'color' => 'purple'],
                ['key' => 'distribusi', 'label' => 'Distribusi', 'value' => $stats['distribusi'], 'icon' => 'fa-warehouse',     'color' => 'yellow'],
                ['key' => 'pengiriman', 'label' => 'Pengiriman', 'value' => $stats['pengiriman'], 'icon' => 'fa-truck-fast',    'color' => 'indigo'],
                ['key' => 'selesai',    'label' => 'Selesai',    'value' => $stats['selesai'],    'icon' => 'fa-circle-check',  'color' => 'green'],
            ];
        @endphp

        <section>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @foreach($statCards as $card)
                <div class="group bg-white rounded-xl shadow-sm p-4 border border-gray-200 transition duration-150 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] font-medium uppercase tracking-wide text-gray-500">{{ $card['label'] }}</div>
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600">
                            <i class="fas {{ $card['icon'] }} text-sm"></i>
                        </span>
                    </div>
                    <div class="mt-3 text-2xl font-bold text-gray-900">{{ $card['value'] }}</div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- SLA Cards --}}
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Service Level Agreement</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 to-white rounded-xl shadow-sm p-5 border border-amber-200">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-amber-100 text-amber-600">
                            <i class="fas fa-triangle-exclamation text-lg"></i>
                        </span>
                        <div>
                            <div class="text-xs font-medium uppercase tracking-wide text-amber-600">SLA Warning</div>
                            <div class="text-3xl font-bold text-amber-700 leading-tight">{{ $slaWarning }}</div>
                            <div class="text-xs text-amber-600/80">Belum diproses 1&ndash;2 hari</div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden bg-gradient-to-br from-red-50 to-white rounded-xl shadow-sm p-5 border border-red-200">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-red-100 text-red-600">
                            <i class="fas fa-circle-exclamation text-lg"></i>
                        </span>
                        <div>
                            <div class="text-xs font-medium uppercase tracking-wide text-red-600">SLA Breach</div>
                            <div class="text-3xl font-bold text-red-700 leading-tight">{{ $slaBreach }}</div>
                            <div class="text-xs text-red-600/80">Belum diproses &gt; 2 hari</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Garansi --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between gap-3">
                    <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-clock-rotate-left text-blue-600"></i>
                        Data Garansi Terbaru
                    </h2>
                    <a href="{{ route('garansi.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 whitespace-nowrap">
                        Lihat Semua <i class="fas fa-arrow-right text-xs ml-0.5"></i>
                    </a>
                </div>

                {{-- SLA Legend --}}
                <div class="px-6 py-2.5 bg-gray-50 border-b border-gray-200 flex flex-wrap items-center gap-x-5 gap-y-1 text-xs text-gray-500">
                    <span class="font-medium text-gray-600">Indikator SLA:</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-yellow-300"></span> Idle 1&ndash;2 hari</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-red-300"></span> Idle &gt; 2 hari</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="text-left px-6 py-3 font-medium text-xs uppercase tracking-wide">Nama</th>
                                <th class="text-left px-6 py-3 font-medium text-xs uppercase tracking-wide">Invoice</th>
                                <th class="text-left px-6 py-3 font-medium text-xs uppercase tracking-wide">Lokasi</th>
                                <th class="text-left px-6 py-3 font-medium text-xs uppercase tracking-wide">Status</th>
                                <th class="text-right px-6 py-3 font-medium text-xs uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentGaransis as $garansi)
                            @php
                                $idleDays = now()->diffInDays($garansi->updated_at);
                                $slaLevel = 'none';
                                if ($garansi->status !== 'selesai') {
                                    if ($idleDays >= 2) $slaLevel = 'breach';
                                    elseif ($idleDays >= 1) $slaLevel = 'warning';
                                }
                                $rowClass = match ($slaLevel) {
                                    'breach'  => 'bg-red-50 hover:bg-red-100',
                                    'warning' => 'bg-yellow-50 hover:bg-yellow-100',
                                    default   => 'hover:bg-gray-50',
                                };
                                $barClass = match ($slaLevel) {
                                    'breach'  => 'bg-red-400',
                                    'warning' => 'bg-yellow-300',
                                    default   => 'bg-transparent',
                                };
                            @endphp
                            <tr class="{{ $rowClass }} transition-colors">
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    <div class="flex items-center gap-2.5">
                                        <span class="h-8 w-1 rounded-full {{ $barClass }}"></span>
                                        <span>{{ $garansi->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $garansi->invoice_pembelian ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ ucfirst($garansi->lokasi_chat) }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $garansi->status_color }}">
                                        {{ ucfirst($garansi->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('garansi.show', $garansi) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium">
                                        Detail <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>
                                    Belum ada data garansi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Audit Log --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-wave-square text-blue-600"></i>
                        Aktivitas Terbaru
                    </h2>
                </div>
                <div class="p-5 max-h-96 overflow-y-auto">
                    @forelse($activities as $activity)
                    <div class="relative pl-6 pb-5 last:pb-0">
                        <span class="absolute left-0 top-1.5 h-2.5 w-2.5 rounded-full bg-blue-500 ring-4 ring-blue-100"></span>
                        @unless($loop->last)
                            <span class="absolute left-[4px] top-4 bottom-0 w-px bg-gray-200"></span>
                        @endunless
                        <p class="text-sm text-gray-800 leading-snug">
                            <span class="font-semibold">{{ $activity->causer->name ?? 'Sistem' }}</span>
                            <span class="text-gray-500">{{ $activity->description }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="far fa-clock mr-1"></i>{{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-stream text-3xl mb-2 block text-gray-300"></i>
                        <p class="text-sm">Belum ada aktivitas.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
