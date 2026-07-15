<x-app-layout>
    <div class="relative space-y-8 pb-8">
        <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.12),_transparent_45%),radial-gradient(circle_at_top_right,_rgba(15,23,42,0.08),_transparent_38%)]"></div>

        <header class="flex flex-col gap-6 rounded-[2rem] border border-slate-200/70 bg-white/80 p-6 shadow-[0_18px_50px_rgba(15,23,42,0.06)] backdrop-blur-xl sm:p-8 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                    ringkasan operasional
                </div>

                <div class="space-y-3">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                        Dashboard
                    </h1>
                    <p class="max-w-2xl text-sm leading-6 text-slate-600 sm:text-[15px]">
                        Pantau status garansi, lihat antrean kerja yang paling lama diam, dan tindak lanjuti aktivitas terbaru dari satu tempat.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:min-w-[420px]">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">total</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-slate-950">{{ $stats['total'] }}</div>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50/80 px-4 py-3">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-700">warning</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-amber-900">{{ $slaWarning }}</div>
                </div>
                <div class="rounded-2xl border border-rose-200 bg-rose-50/80 px-4 py-3">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-rose-700">breach</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-rose-900">{{ $slaBreach }}</div>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-700">selesai</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-emerald-900">{{ $stats['selesai'] }}</div>
                </div>
            </div>
        </header>

        @php
            $statCards = [
                ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'fa-hourglass-half', 'tone' => 'slate', 'status' => 'pending'],
                ['label' => 'Repair', 'value' => $stats['repair'], 'icon' => 'fa-screwdriver-wrench', 'tone' => 'blue', 'status' => 'repair'],
                ['label' => 'Replace', 'value' => $stats['replace'], 'icon' => 'fa-arrows-rotate', 'tone' => 'violet', 'status' => 'replace'],
                ['label' => 'Distribusi', 'value' => $stats['distribusi'], 'icon' => 'fa-boxes-stacked', 'tone' => 'amber', 'status' => 'to distribution'],
                ['label' => 'Pengiriman', 'value' => $stats['pengiriman'], 'icon' => 'fa-truck-fast', 'tone' => 'sky', 'status' => 'pengiriman'],
            ];

            $toneMap = [
                'slate' => [
                    'card' => 'border-slate-200 bg-slate-50/80 text-slate-600',
                    'icon' => 'bg-slate-900 text-white',
                ],
                'blue' => [
                    'card' => 'border-blue-200 bg-blue-50/80 text-blue-600',
                    'icon' => 'bg-blue-600 text-white',
                ],
                'violet' => [
                    'card' => 'border-violet-200 bg-violet-50/80 text-violet-600',
                    'icon' => 'bg-violet-600 text-white',
                ],
                'amber' => [
                    'card' => 'border-amber-200 bg-amber-50/80 text-amber-600',
                    'icon' => 'bg-amber-600 text-white',
                ],
                'sky' => [
                    'card' => 'border-sky-200 bg-sky-50/80 text-sky-600',
                    'icon' => 'bg-sky-600 text-white',
                ],
            ];
        @endphp

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($statCards as $card)
                <a
                    href="{{ route('garansi.index', ['status' => $card['status']]) }}"
                    class="group flex items-center gap-4 rounded-[1.5rem] border p-4 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 {{ $toneMap[$card['tone']]['card'] }}"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-sm {{ $toneMap[$card['tone']]['icon'] }}">
                        <i class="fas {{ $card['icon'] }} text-sm"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">
                            {{ $card['label'] }}
                        </div>
                        <div class="mt-1 text-2xl font-semibold tabular-nums tracking-tight text-slate-950">
                            {{ $card['value'] }}
                        </div>
                    </div>

                    <div class="ml-auto text-slate-400 transition-transform duration-200 group-hover:translate-x-1">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </a>
            @endforeach
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <article class="rounded-[2rem] border border-amber-200/80 bg-gradient-to-br from-amber-50 to-white p-6 shadow-[0_16px_40px_rgba(120,53,15,0.08)]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            perhatian
                        </div>
                        <h2 class="mt-4 text-xl font-semibold tracking-tight text-slate-950">
                            Garansi yang mulai diam
                        </h2>
                        <p class="mt-2 max-w-md text-sm leading-6 text-slate-600">
                            Item yang tidak bergerak 1–2 hari perlu dipantau agar tidak menumpuk ke tahap keterlambatan.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-white p-4 text-right shadow-sm">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-700">warning</div>
                        <div class="mt-1 text-4xl font-semibold tabular-nums tracking-tight text-amber-900">{{ $slaWarning }}</div>
                    </div>
                </div>
            </article>

            <article class="rounded-[2rem] border border-rose-200/80 bg-gradient-to-br from-rose-50 to-white p-6 shadow-[0_16px_40px_rgba(127,29,29,0.08)]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-rose-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            segera tindak
                        </div>
                        <h2 class="mt-4 text-xl font-semibold tracking-tight text-slate-950">
                            Garansi melewati SLA
                        </h2>
                        <p class="mt-2 max-w-md text-sm leading-6 text-slate-600">
                            Item yang diam lebih dari 2 hari perlu diprioritaskan agar alur layanan tetap rapi.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-rose-200 bg-white p-4 text-right shadow-sm">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-rose-700">breach</div>
                        <div class="mt-1 text-4xl font-semibold tabular-nums tracking-tight text-rose-900">{{ $slaBreach }}</div>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,0.9fr)]">
            <article class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-[0_18px_50px_rgba(15,23,42,0.06)]">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-950">Data garansi terbaru</h2>
                        <p class="mt-1 text-sm text-slate-500">Lima data paling baru untuk kontrol cepat.</p>
                    </div>
                    <a href="{{ route('garansi.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        Lihat semua
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 text-[11px] uppercase tracking-[0.22em] text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama</th>
                                <th class="px-6 py-4 font-semibold">Invoice</th>
                                <th class="px-6 py-4 font-semibold">Lokasi</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentGaransis as $garansi)
                                @php
                                    $idleDays = $garansi->updated_at->copy()->startOfDay()->diffInDays(now()->copy()->startOfDay());
                                    $belumSelesai = strtolower(trim($garansi->status)) !== 'selesai';
                                    $urgency = '';

                                    if ($belumSelesai) {
                                        if ($idleDays >= 2) {
                                            $urgency = 'border-l-4 border-rose-500';
                                        } elseif ($idleDays >= 1) {
                                            $urgency = 'border-l-4 border-amber-500';
                                        }
                                    }
                                @endphp

                                <tr class="group hover:bg-slate-50/70">
                                    <td class="px-6 py-4 font-medium text-slate-950 {{ $urgency }}">
                                        <div class="max-w-[220px] truncate">{{ $garansi->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                        {{ $garansi->invoice_pembelian ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ ucfirst($garansi->lokasi_chat) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $garansi->status_color }}">
                                            {{ ucfirst($garansi->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('garansi.show', $garansi) }}" class="font-medium text-blue-600 transition hover:text-blue-700">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center">
                                        <div class="mx-auto max-w-sm space-y-2">
                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <p class="text-sm font-medium text-slate-900">Belum ada data garansi.</p>
                                            <p class="text-sm text-slate-500">Data baru akan muncul di sini setelah klaim masuk.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <aside class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-[0_18px_50px_rgba(15,23,42,0.06)]">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-950">Aktivitas terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500">Log tindakan terakhir yang terekam di sistem.</p>
                </div>

                <div class="max-h-[30rem] space-y-1 overflow-y-auto p-4">
                    @forelse ($activities as $activity)
                        @php
                            // Parse activity untuk tampil detail dengan nama garansi
                            $icon = 'fa-bolt';
                            $description = $activity->description;
                            $garansiName = '';
                            $detail = '';
                            
                            // Ambil nama garansi dari subject (yang di-update/di-create)
                            if ($activity->subject && method_exists($activity->subject, 'getAttribute')) {
                                $garansiName = $activity->subject->nama ?? 'Garansi';
                            }
                            
                            // Cek tipe activity dari event
                            if ($activity->event === 'created') {
                                $icon = 'fa-plus-circle';
                                $itemCount = $activity->subject->items->count() ?? 0;
                                $description = "Membuat data garansi";
                                $detail = "$garansiName ($itemCount item)";
                            } elseif ($activity->event === 'updated') {
                                $icon = 'fa-pen';
                                
                                // Cek changes dari properties
                                if ($activity->properties && isset($activity->properties['old']) && isset($activity->properties['attributes'])) {
                                    $old = $activity->properties['old'];
                                    $new = $activity->properties['attributes'];
                                    
                                    // Update Status
                                    if (isset($new['status']) && isset($old['status']) && $new['status'] != $old['status']) {
                                        $icon = 'fa-circle-chevron-right';
                                        $description = "Update status garansi";
                                        $detail = "$garansiName: {$old['status']} → {$new['status']}";
                                    }
                                    // Update SN Baru (Replace)
                                    elseif (isset($new['serial_number_baru']) && $new['serial_number_baru'] && (!isset($old['serial_number_baru']) || !$old['serial_number_baru'])) {
                                        $icon = 'fa-microchip';
                                        $description = "Tambah SN baru";
                                        $detail = "$garansiName: {$new['serial_number_baru']}";
                                    }
                                    // Update Catatan
                                    elseif (isset($new['catatan']) && isset($old['catatan']) && $new['catatan'] != $old['catatan']) {
                                        $icon = 'fa-note-sticky';
                                        $description = "Update catatan";
                                        $detail = $garansiName;
                                    }
                                    // Update Resi
                                    elseif (isset($new['resi_pengiriman']) && isset($old['resi_pengiriman']) && $new['resi_pengiriman'] != $old['resi_pengiriman']) {
                                        $icon = 'fa-barcode';
                                        $description = "Update resi pengiriman";
                                        $detail = "$garansiName: {$new['resi_pengiriman']}";
                                    }
                                    // Update lainnya
                                    else {
                                        $description = "Update data garansi";
                                        $detail = $garansiName;
                                    }
                                } else {
                                    $description = "Update data garansi";
                                    $detail = $garansiName;
                                }
                            }
                        @endphp

                        <article class="rounded-2xl px-4 py-3 transition hover:bg-slate-50">
                            <div class="flex gap-3">
                                <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                    <i class="fas {{ $icon }} text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm leading-6 text-slate-700">
                                        <span class="font-semibold text-slate-950">{{ $activity->causer->name ?? 'Sistem' }}</span>
                                        <span class="text-slate-500">{{ $description }}</span>
                                    </p>
                                    @if ($detail)
                                        <p class="mt-1 truncate text-xs font-mono text-slate-500">
                                            {{ $detail }}
                                        </p>
                                    @endif
                                    <p class="mt-1 text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="px-4 py-14 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <i class="fas fa-clock-rotate-left"></i>
                            </div>
                            <p class="mt-3 text-sm font-medium text-slate-900">Belum ada aktivitas.</p>
                            <p class="mt-1 text-sm text-slate-500">Interaksi terbaru akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </aside>
        </section>
    </div>
</x-app-layout>