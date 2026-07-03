@extends('layouts.app')
@section('title', 'Detail Garansi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Garansi</h1>
        <div class="flex gap-2">
            <a href="{{ route('garansi.edit', $garansi) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('garansi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Status Bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="text-xs text-gray-400 mb-1">Status Saat Ini</div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $garansi->status_color }}">
                    {{ ucfirst($garansi->status) }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <select id="status-select" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @foreach($statusList as $status)
                    <option value="{{ $status }}" {{ $garansi->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <input type="text" id="catatan-status" placeholder="Catatan (opsional)..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-48 outline-none focus:ring-2 focus:ring-blue-500">
                <button id="btn-update-status"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Update Status
                </button>
            </div>
        </div>
        @if($garansi->catatan)
        <div class="mt-3 pt-3 border-t border-gray-100 text-sm text-gray-600">
            <strong>Catatan:</strong> {{ $garansi->catatan }}
        </div>
        @endif
    </div>

    {{-- Detail Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Data Customer --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-500"></i> Data Customer
            </h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-400">Nama</dt>
                    <dd class="font-medium text-gray-900">{{ $garansi->nama }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">No HP</dt>
                    <dd class="font-medium text-gray-900">{{ $garansi->no_hp }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Invoice Pembelian</dt>
                    <dd class="font-medium text-gray-900">{{ $garansi->invoice_pembelian ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Marketplace</dt>
                    <dd class="font-medium text-gray-900">{{ $garansi->nama_marketplace }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Tanggal Beli</dt>
                    <dd class="font-medium text-gray-900">{{ $garansi->tanggal_beli->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Tanggal Sampai</dt>
                    <dd class="font-medium text-gray-900">{{ $garansi->tanggal_sampai->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Lokasi Chat & Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fab fa-whatsapp text-green-500"></i> Lokasi Chat WhatsApp
            </h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <i class="fab fa-whatsapp text-3xl text-green-500"></i>
                    <div>
                        <div class="font-semibold text-gray-900">{{ ucfirst($garansi->lokasi_chat) }}</div>
                        <div class="text-sm text-gray-500">{{ config("whatsapp.lokasi.{$garansi->lokasi_chat}.nomor") }}</div>
                    </div>
                </div>
            </div>

            <h3 class="font-semibold text-gray-900 mt-5 mb-3 flex items-center gap-2">
                <i class="fas fa-tools text-orange-500"></i> Kerusakan
            </h3>
            <p class="text-sm text-gray-700 bg-orange-50 border border-orange-200 rounded-lg p-3">{{ $garansi->kerusakan }}</p>

            <h3 class="font-semibold text-gray-900 mt-5 mb-3 flex items-center gap-2">
                <i class="fas fa-boxes text-purple-500"></i> Kelengkapan Barang
            </h3>
            <p class="text-sm text-gray-700 bg-purple-50 border border-purple-200 rounded-lg p-3">{{ $garansi->kelengkapan_barang }}</p>
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-laptop text-indigo-500"></i> Daftar Barang ({{ $garansi->items->count() }})
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-2 font-medium">#</th>
                        <th class="text-left px-4 py-2 font-medium">Nama Barang</th>
                        <th class="text-left px-4 py-2 font-medium">Serial Number</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($garansi->items as $index => $item)
                    <tr>
                        <td class="px-4 py-2 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 font-medium text-gray-900">{{ $item->nama_barang }}</td>
                        <td class="px-4 py-2 text-gray-600 font-mono text-xs">{{ $item->serial_number }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- WhatsApp Logs --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <i class="fab fa-whatsapp text-green-500"></i> Riwayat WhatsApp
            </h3>
            <button id="btn-send-wa" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-1">
                <i class="fas fa-paper-plane"></i> Kirim WA Manual
            </button>
        </div>

        {{-- Form Kirim WA Manual (hidden by default) --}}
        <div id="wa-form" class="hidden mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <textarea id="wa-message" rows="4" placeholder="Tulis pesan WhatsApp..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-green-500"></textarea>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" id="btn-cancel-wa" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-sm">Batal</button>
                <button type="button" id="btn-send-wa-confirm" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium">Kirim</button>
            </div>
        </div>

        @if($garansi->whatsappLogs->isNotEmpty())
        <div class="space-y-2">
            @foreach($garansi->whatsappLogs as $log)
            <div class="border border-gray-200 rounded-lg p-3 flex items-start gap-3">
                <div class="flex-shrink-0">
                    @if($log->status_kirim === 'terkirim')
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    @elseif($log->status_kirim === 'gagal')
                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                    @else
                        <i class="fas fa-clock text-gray-400 text-lg"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                        <span class="font-medium text-gray-600">{{ strtoupper($log->tipe) }}</span>
                        <span>•</span>
                        <span>{{ ucfirst($log->lokasi) }}</span>
                        <span>•</span>
                        <span>{{ $log->created_at->format('d/m/Y H:i') }}</span>
                        <span class="px-1.5 py-0.5 rounded-full text-xs
                            {{ $log->status_kirim === 'terkirim' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $log->status_kirim === 'gagal' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $log->status_kirim === 'pending' ? 'bg-gray-100 text-gray-700' : '' }}">
                            {{ ucfirst($log->status_kirim) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $log->pesan }}</p>
                    @if($log->status_kirim === 'gagal' && $log->response_api)
                        <div class="mt-2 bg-red-50 border border-red-200 rounded p-2 text-xs text-red-600 overflow-x-auto">
                            <strong>WAHA Error:</strong> {{ $log->response_api }}
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat WhatsApp.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
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