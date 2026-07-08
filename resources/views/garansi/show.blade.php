@extends('layouts.app')
@section('title', 'Detail Garansi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Detail Garansi</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $garansi->nama }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('garansi.edit', $garansi) }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition-colors">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('garansi.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Status Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="text-xs text-slate-400 mb-1.5">Status Saat Ini</div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $garansi->status_color }}">
                    {{ ucfirst($garansi->status) }}
                </span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select id="status-select" class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ $garansi->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <input type="text" id="catatan-status" placeholder="Catatan (opsional)..."
                    class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm w-48 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <button id="btn-update-status"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                    Update Status
                </button>
            </div>
        </div>
        @if($garansi->catatan)
        <div class="mt-4 pt-4 border-t border-slate-100 text-sm text-slate-600">
            <span class="font-medium text-slate-800">Catatan:</span> {{ $garansi->catatan }}
        </div>
        @endif
    </div>

    {{-- Detail Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Data Customer --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-500"></i> Data Customer
            </h3>
            <dl class="space-y-3.5 text-sm">
                <div class="flex items-center justify-between">
                    <dt class="text-slate-400">Nama</dt>
                    <dd class="font-medium text-slate-900">{{ $garansi->nama }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-400">No HP</dt>
                    <dd class="font-medium text-slate-900">{{ $garansi->no_hp }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-400">Invoice Pembelian</dt>
                    <dd class="font-medium text-slate-900 font-mono text-xs">{{ $garansi->invoice_pembelian ?? '-' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-400">Marketplace</dt>
                    <dd class="font-medium text-slate-900">{{ $garansi->nama_marketplace }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-400">Tanggal Beli</dt>
                    <dd class="font-medium text-slate-900">{{ $garansi->tanggal_beli->format('d/m/Y') }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-400">Tanggal Sampai</dt>
                    <dd class="font-medium text-slate-900">{{ $garansi->tanggal_sampai->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Lokasi Chat & Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fab fa-whatsapp text-emerald-500"></i> Lokasi Chat WhatsApp
            </h3>
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <i class="fab fa-whatsapp text-3xl text-emerald-500"></i>
                    <div>
                        <div class="font-semibold text-slate-900">{{ ucfirst($garansi->lokasi_chat) }}</div>
                        <div class="text-sm text-slate-500">{{ config("whatsapp.lokasi.{$garansi->lokasi_chat}.nomor") }}</div>
                    </div>
                </div>
            </div>

            <h3 class="font-semibold text-slate-900 mt-5 mb-2.5 flex items-center gap-2">
                <i class="fas fa-screwdriver-wrench text-amber-500"></i> Kerusakan
            </h3>
            <p class="text-sm text-slate-700 bg-amber-50 border border-amber-100 rounded-xl p-3.5">{{ $garansi->kerusakan }}</p>

            <h3 class="font-semibold text-slate-900 mt-5 mb-2.5 flex items-center gap-2">
                <i class="fas fa-boxes-stacked text-violet-500"></i> Kelengkapan Barang
            </h3>
            <p class="text-sm text-slate-700 bg-violet-50 border border-violet-100 rounded-xl p-3.5">{{ $garansi->kelengkapan_barang }}</p>
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-laptop text-blue-500"></i> Daftar Barang <span class="text-slate-400 font-normal">({{ $garansi->items->count() }})</span>
        </h3>
         <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-4 py-2 font-medium">#</th>
                        <th class="text-left px-4 py-2 font-medium">Nama Barang</th>
                        <th class="text-left px-4 py-2 font-medium">SN Lama</th>
                        <th class="text-left px-4 py-2 font-medium">SN Baru (Replace)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($garansi->items as $index => $item)
                    <tr data-item-row="{{ $item->id }}">
                        <td class="px-4 py-2.5 text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-2.5 font-medium text-slate-900">{{ $item->nama_barang }}</td>
                        <td class="px-4 py-2.5 text-slate-600 font-mono text-xs">{{ $item->serial_number }}</td>
                        <td class="px-4 py-2.5">
                            <div class="sn-baru-display {{ $item->is_replaced ? '' : 'hidden' }} flex items-center gap-2">
                                <span class="font-mono text-xs font-medium text-purple-700 bg-purple-50 border border-purple-100 rounded-lg px-2 py-1 sn-baru-text">{{ $item->serial_number_baru }}</span>
                                <span class="text-xs text-slate-400 sn-baru-tanggal">{{ $item->replaced_at?->format('d/m/Y H:i') }}</span>
                                <button type="button" class="btn-edit-sn text-xs text-blue-600 hover:text-blue-800" data-item-id="{{ $item->id }}">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                            <div class="sn-baru-form {{ $item->is_replaced ? 'hidden' : '' }} flex items-center gap-2">
                                <input type="text" class="sn-baru-input border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-mono w-40 outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                    placeholder="Input SN baru..." value="{{ $item->serial_number_baru }}">
                                <button type="button" class="btn-save-sn bg-purple-600 hover:bg-purple-700 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium transition-colors"
                                    data-url="{{ route('garansi.items.replace-sn', [$garansi->id, $item->id]) }}">
                                    Simpan
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- WhatsApp Logs --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                <i class="fab fa-whatsapp text-emerald-500"></i> Riwayat WhatsApp
            </h3>
            <button id="btn-send-wa" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-1.5 transition-colors">
                <i class="fas fa-paper-plane"></i> Kirim WA Manual
            </button>
        </div>

        {{-- Form Kirim WA Manual (hidden by default) --}}
        <div id="wa-form" class="hidden mb-4 bg-slate-50 border border-slate-200 rounded-xl p-4">
            <textarea id="wa-message" rows="4" placeholder="Tulis pesan WhatsApp..."
                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"></textarea>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" id="btn-cancel-wa" class="bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg text-sm transition-colors">Batal</button>
                <button type="button" id="btn-send-wa-confirm" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">Kirim</button>
            </div>
        </div>

        @if($garansi->whatsappLogs->isNotEmpty())
        <div class="space-y-2">
            @foreach($garansi->whatsappLogs as $log)
            @php
                $statusIcon = match($log->status_kirim) {
                    'terkirim' => ['fa-check-circle', 'text-emerald-500'],
                    'gagal' => ['fa-times-circle', 'text-rose-500'],
                    default => ['fa-clock', 'text-slate-400'],
                };
                $statusBadge = match($log->status_kirim) {
                    'terkirim' => 'bg-emerald-50 text-emerald-700',
                    'gagal' => 'bg-rose-50 text-rose-700',
                    default => 'bg-slate-100 text-slate-600',
                };
            @endphp
            <div class="border border-slate-100 rounded-xl p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas {{ $statusIcon[0] }} {{ $statusIcon[1] }} text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1 flex-wrap">
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <span class="font-medium text-slate-600">{{ strtoupper($log->tipe) }}</span>
                            <span>•</span>
                            <span>{{ ucfirst($log->lokasi) }}</span>
                            <span>•</span>
                            <span>{{ $log->created_at->format('d/m/Y H:i') }}</span>
                            <span class="px-1.5 py-0.5 rounded-full text-xs {{ $statusBadge }}">
                                {{ ucfirst($log->status_kirim) }}
                            </span>
                        </div>
                        <button class="resend-wa-btn text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1" data-url="{{ route('garansi.resend-wa', [$garansi->id, $log->id]) }}">
                            <i class="fas fa-rotate-right"></i> Kirim Ulang
                        </button>
                    </div>
                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $log->pesan }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-slate-400 text-center py-8">
            <i class="fab fa-whatsapp text-2xl mb-2 block"></i>
            Belum ada riwayat WhatsApp.
        </p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
     // ========== Replace SN (Serial Number Baru) ==========
    document.querySelectorAll('.btn-save-sn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            const row = this.closest('[data-item-row]');
            const input = row.querySelector('.sn-baru-input');
            const snBaru = input.value.trim();

            if (!snBaru) {
                alert('SN baru tidak boleh kosong.');
                return;
            }

            const btn = this;
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ serial_number_baru: snBaru })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menyimpan SN baru.');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    row.querySelector('.sn-baru-text').textContent = data.item.serial_number_baru;
                    row.querySelector('.sn-baru-tanggal').textContent = data.item.replaced_at
                        ? new Date(data.item.replaced_at).toLocaleString('id-ID')
                        : '';
                    row.querySelector('.sn-baru-form').classList.add('hidden');
                    row.querySelector('.sn-baru-display').classList.remove('hidden');
                    alert('SN baru tersimpan. Notifikasi WhatsApp sedang dikirim ke customer.');
                } else {
                    alert('Gagal: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                btn.disabled = false;
                btn.textContent = originalText;
            });
        });
    });

    document.querySelectorAll('.btn-edit-sn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('[data-item-row]');
            row.querySelector('.sn-baru-display').classList.add('hidden');
            row.querySelector('.sn-baru-form').classList.remove('hidden');
            row.querySelector('.sn-baru-input').focus();
        });
    });
    // ========== Kirim Ulang WA ==========
    document.querySelectorAll('.resend-wa-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            const btn = this;
            const originalHTML = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Server Error (500)');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    alert('Pesan berhasil dikirim ulang!');
                    location.reload();
                } else {
                    alert('Gagal: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            });
        });
    });

    // ========== Update Status ==========
    document.getElementById('btn-update-status').addEventListener('click', function() {
        const status = document.getElementById('status-select').value;
        const catatan = document.getElementById('catatan-status').value;
        const btn = this;
        const originalText = btn.textContent;

        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        fetch('{{ route("garansi.status", $garansi) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: status, catatan: catatan })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
                btn.disabled = false;
                btn.textContent = originalText;
            }
        })
        .catch(err => {
            alert('Error: ' + err.message);
            btn.disabled = false;
            btn.textContent = originalText;
        });
    });

    // ========== Kirim WA Manual ==========
    document.getElementById('btn-send-wa').addEventListener('click', function() {
        document.getElementById('wa-form').classList.toggle('hidden');
    });

    document.getElementById('btn-cancel-wa').addEventListener('click', function() {
        document.getElementById('wa-form').classList.add('hidden');
        document.getElementById('wa-message').value = '';
    });

    document.getElementById('btn-send-wa-confirm').addEventListener('click', function() {
        const pesan = document.getElementById('wa-message').value.trim();
        if (!pesan) {
            alert('Pesan tidak boleh kosong.');
            return;
        }

        const btn = this;
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        fetch('{{ route("garansi.send-wa", $garansi) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ pesan: pesan })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Pesan berhasil dikirim!');
                location.reload();
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            alert('Error: ' + err.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = originalText;
        });
    });
</script>
@endpush