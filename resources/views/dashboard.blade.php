<x-app-layout>
    <div class="space-y-6 pb-12">
        {{-- Header Section --}}
        <header class="space-y-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">
                        Dashboard
                    </h1>
                    <p class="mt-2 text-base text-slate-600">
                        Ringkasan status garansi dan aktivitas sistem real-time
                    </p>
                </div>
                <a href="{{ route('garansi.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg shadow-blue-600/20 active:scale-95 transition-all duration-200 w-full md:w-auto">
                    <i class="fas fa-plus text-sm"></i> Tambah Data Garansi
                </a>
            </div>
        </header>

        {{-- KPI Cards Grid --}}
        <div class="grid grid-cols-2 gap-3 md:gap-4 lg:grid-cols-5">
            <!-- Total Unit -->
            <div class="bg-white rounded-xl border border-slate-200/60 p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Unit</span>
                    <div class="w-9 h-9 bg-blue-50/80 rounded-lg flex items-center justify-center text-blue-600">
                        <i class="fas fa-boxes text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    {{ $stats['total'] }}
                </div>
                <p class="mt-2 text-xs text-slate-500">Semua data garansi</p>
            </div>

            <!-- SLA Warning -->
            <div class="bg-white rounded-xl border border-amber-200/50 p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-amber-500/5 rounded-full -mr-10 -mt-10"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <span class="text-xs font-semibold uppercase tracking-wide text-amber-700">⚠ Warning</span>
                    <div class="w-9 h-9 bg-amber-50/80 rounded-lg flex items-center justify-center text-amber-600">
                        <i class="fas fa-triangle-exclamation text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl md:text-4xl font-black text-amber-600 tracking-tight relative z-10">
                    {{ $slaWarning }}
                </div>
                <p class="mt-2 text-xs text-amber-600/70 relative z-10">Diam 1-2 hari</p>
            </div>

            <!-- SLA Breach -->
            <div class="bg-white rounded-xl border border-rose-200/50 p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-rose-500/5 rounded-full -mr-10 -mt-10"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <span class="text-xs font-semibold uppercase tracking-wide text-rose-700">⛔ Breach</span>
                    <div class="w-9 h-9 bg-rose-50/80 rounded-lg flex items-center justify-center text-rose-600">
                        <i class="fas fa-circle-xmark text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl md:text-4xl font-black text-rose-600 tracking-tight relative z-10">
                    {{ $slaBreach }}
                </div>
                <p class="mt-2 text-xs text-rose-600/70 relative z-10">Melewati SLA</p>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-xl border border-emerald-200/50 p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/5 rounded-full -mr-10 -mt-10"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700">✓ Selesai</span>
                    <div class="w-9 h-9 bg-emerald-50/80 rounded-lg flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl md:text-4xl font-black text-emerald-600 tracking-tight relative z-10">
                    {{ $stats['selesai'] }}
                </div>
                <p class="mt-2 text-xs text-emerald-600/70 relative z-10">Garansi selesai</p>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-xl border border-slate-200/60 p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending</span>
                    <div class="w-9 h-9 bg-slate-100/80 rounded-lg flex items-center justify-center text-slate-600">
                        <i class="fas fa-hourglass-half text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    {{ $stats['pending'] }}
                </div>
                <p class="mt-2 text-xs text-slate-500">Menunggu proses</p>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="grid gap-4 lg:grid-cols-3">
            <!-- Repair -->
            <div class="bg-white rounded-xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-50/80 rounded-lg flex items-center justify-center text-blue-600">
                        <i class="fas fa-wrench text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-sm uppercase tracking-wide">Perbaikan</h3>
                </div>
                <div class="text-4xl font-black text-slate-900 tracking-tight">{{ $stats['repair'] }}</div>
                <p class="mt-3 text-sm text-slate-600">Sedang dalam perbaikan</p>
            </div>

            <!-- Replace -->
            <div class="bg-white rounded-xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-50/80 rounded-lg flex items-center justify-center text-purple-600">
                        <i class="fas fa-arrow-right-arrow-left text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-sm uppercase tracking-wide">Penggantian</h3>
                </div>
                <div class="text-4xl font-black text-slate-900 tracking-tight">{{ $stats['replace'] }}</div>
                <p class="mt-3 text-sm text-slate-600">Perlu unit pengganti</p>
            </div>

            <!-- Distribusi -->
            <div class="bg-white rounded-xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-50/80 rounded-lg flex items-center justify-center text-indigo-600">
                        <i class="fas fa-truck text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-sm uppercase tracking-wide">Distribusi</h3>
                </div>
                <div class="text-4xl font-black text-slate-900 tracking-tight">{{ $stats['distribusi'] }}</div>
                <p class="mt-3 text-sm text-slate-600">Dalam tahap distribusi</p>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid gap-4 lg:grid-cols-3">
            {{-- Recent Garansis Table --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/60 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200/40 bg-gradient-to-r from-slate-50/50 to-transparent">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-slate-900 flex items-center gap-2.5">
                            <i class="fas fa-list-check text-blue-600"></i>
                            Data Garansi Terbaru
                        </h2>
                        <a href="{{ route('garansi.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50/50 border-b border-slate-200/40">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Invoice</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Lokasi</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Status</th>
                                <th class="px-6 py-3 text-center font-semibold text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/40">
                            @forelse ($recentGaransis as $garansi)
                                @php
                                    $idleDays = $garansi->updated_at->copy()->startOfDay()->diffInDays(now()->copy()->startOfDay());
                                    $belumSelesai = strtolower(trim($garansi->status)) !== 'selesai';
                                    $rowClass = '';

                                    if ($belumSelesai) {
                                        if ($idleDays >= 2) {
                                            $rowClass = 'bg-rose-50/30 hover:bg-rose-50/50';
                                        } elseif ($idleDays >= 1) {
                                            $rowClass = 'bg-amber-50/30 hover:bg-amber-50/50';
                                        } else {
                                            $rowClass = 'hover:bg-slate-50/50';
                                        }
                                    } else {
                                        $rowClass = 'hover:bg-slate-50/50';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }} transition-colors duration-200">
                                    <td class="px-6 py-3 font-medium text-slate-900">
                                        {{ Str::limit($garansi->nama, 20) }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 font-mono text-xs">
                                        {{ $garansi->invoice_pembelian ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 text-xs uppercase tracking-wide">
                                        {{ $garansi->lokasi_chat }}
                                    </td>
                                    <td class="px-6 py-3">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-slate-100/80 text-slate-700',
                                                'repair' => 'bg-blue-100/80 text-blue-700',
                                                'replace' => 'bg-purple-100/80 text-purple-700',
                                                'distribusi' => 'bg-indigo-100/80 text-indigo-700',
                                                'pengiriman' => 'bg-cyan-100/80 text-cyan-700',
                                                'selesai' => 'bg-emerald-100/80 text-emerald-700',
                                            ];
                                            $statusColor = $statusColors[strtolower($garansi->status)] ?? 'bg-slate-100/80 text-slate-700';
                                        @endphp
                                        <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst($garansi->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <a href="{{ route('garansi.show', $garansi) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150">
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                        <i class="fas fa-inbox text-2xl mb-2 opacity-40"></i>
                                        <p class="text-sm">Belum ada data garansi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Activity Log Sidebar --}}
            <aside class="bg-white rounded-xl border border-slate-200/60 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-4 border-b border-slate-200/40 bg-gradient-to-r from-slate-50/50 to-transparent">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2.5">
                        <i class="fas fa-clock text-blue-600"></i>
                        Aktivitas Terbaru
                    </h2>
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-slate-200/40">
                    @forelse ($activities as $activity)
                        <div class="px-6 py-3 hover:bg-slate-50/50 transition-colors duration-200">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-medium text-slate-900">
                                        <span class="font-semibold text-blue-600">{{ $activity->causer->name ?? 'Sistem' }}</span>
                                        <span class="text-slate-600">{{ $activity->description }}</span>
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-slate-500 flex-1 flex items-center justify-center">
                            <p class="text-sm">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>