<x-app-layout>
    <div class="space-y-6">

        {{-- Page heading --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-0.5">Ringkasan status garansi hari ini</p>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            @php
                $statCards = [
                    ['label' => 'Total', 'value' => $stats['total'], 'icon' => 'fa-layer-group', 'color' => 'slate', 'status' => null],
                    ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'fa-hourglass-half', 'color' => 'slate', 'status' => 'pending'],
                    ['label' => 'Repair', 'value' => $stats['repair'], 'icon' => 'fa-screwdriver-wrench', 'color' => 'blue', 'status' => 'repair'],
                    ['label' => 'Replace', 'value' => $stats['replace'], 'icon' => 'fa-arrows-rotate', 'color' => 'violet', 'status' => 'replace'],
                    ['label' => 'Distribusi', 'value' => $stats['distribusi'], 'icon' => 'fa-boxes-stacked', 'color' => 'amber', 'status' => 'to distribution'],
                    ['label' => 'Pengiriman', 'value' => $stats['pengiriman'], 'icon' => 'fa-truck-fast', 'color' => 'sky', 'status' => 'pengiriman'],
                    ['label' => 'Selesai', 'value' => $stats['selesai'], 'icon' => 'fa-circle-check', 'color' => 'emerald', 'status' => 'selesai'],
                ];
                $colorMap = [
                    'slate'   => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500'],
                    'blue'    => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                    'violet'  => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
                    'amber'   => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                    'sky'     => ['bg' => 'bg-sky-50', 'text' => 'text-sky-600'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                ];
            @endphp
            @foreach($statCards as $card)
            <a href="{{ route('garansi.index', $card['status'] ? ['status' => $card['status']] : []) }}"
               class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center gap-3 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 cursor-pointer">
                <div class="w-10 h-10 rounded-xl {{ $colorMap[$card['color']]['bg'] }} {{ $colorMap[$card['color']]['text'] }} flex items-center justify-center shrink-0">
                    <i class="fas {{ $card['icon'] }} text-sm"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs text-slate-400 truncate">{{ $card['label'] }}</div>
                    <div class="text-2xl font-bold text-slate-900 tabular-nums leading-tight">{{ $card['value'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
        {{-- SLA Banner --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-triangle-exclamation text-sm"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-amber-800">SLA Warning</div>
                        <div class="text-xs text-amber-600">Diam 1–2 hari, perlu ditindaklanjuti</div>
                    </div>
                </div>
                <div class="text-3xl font-bold text-amber-700 tabular-nums">{{ $slaWarning }}</div>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50/60 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-fire text-sm"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-rose-800">SLA Breach</div>
                        <div class="text-xs text-rose-600">Diam lebih dari 2 hari, segera tindak lanjut</div>
                    </div>
                </div>
                <div class="text-3xl font-bold text-rose-700 tabular-nums">{{ $slaBreach }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Garansi --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900">Data Garansi Terbaru</h2>
                    <a href="{{ route('garansi.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Lihat Semua <i class="fas fa-arrow-right text-xs ml-0.5"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-slate-400 text-xs uppercase tracking-wide">
                            <tr>
                                <th class="text-left px-6 py-3 font-medium">Nama</th>
                                <th class="text-left px-6 py-3 font-medium">Invoice</th>
                                <th class="text-left px-6 py-3 font-medium">Lokasi</th>
                                <th class="text-left px-6 py-3 font-medium">Status</th>
                                <th class="text-right px-6 py-3 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentGaransis as $garansi)
                            @php
                                $idleDays = now()->diffInDays($garansi->updated_at);
                                $accent = '';
                                if ($garansi->status !== 'selesai') {
                                    if ($idleDays >= 2) $accent = 'border-l-4 border-l-rose-400';
                                    elseif ($idleDays >= 1) $accent = 'border-l-4 border-l-amber-400';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors {{ $accent }}">
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $garansi->nama }}</td>
                                <td class="px-6 py-3 text-slate-500 font-mono text-xs">{{ $garansi->invoice_pembelian ?? '-' }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ ucfirst($garansi->lokasi_chat) }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $garansi->status_color }}">
                                        {{ ucfirst($garansi->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('garansi.show', $garansi) }}" class="text-blue-600 hover:text-blue-700 font-medium text-xs">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Belum ada data.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Audit Log --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-900">Aktivitas Terbaru</h2>
                </div>
                <div class="p-4 space-y-1 max-h-96 overflow-y-auto">
                    @forelse($activities as $activity)
                    <div class="text-sm rounded-lg px-3 py-2.5 hover:bg-slate-50 transition-colors relative pl-4">
                        <span class="absolute left-0 top-3.5 w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                        <p class="text-slate-700 leading-snug">
                            <span class="font-semibold text-slate-900">{{ $activity->causer->name ?? 'Sistem' }}</span>
                            <span class="text-slate-500">{{ $activity->description }}</span>
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-8">
                        <i class="fas fa-clock-rotate-left text-xl mb-2 block"></i>
                        Belum ada aktivitas.
                    </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>