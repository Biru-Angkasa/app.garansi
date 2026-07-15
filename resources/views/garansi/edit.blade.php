@extends('layouts.app')
@section('title', 'Edit Garansi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Data Garansi</h1>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui detail klaim garansi</p>
        </div>
        <a href="{{ route('garansi.show', $garansi) }}" class="text-slate-500 hover:text-slate-800 text-sm font-medium flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('garansi.update', $garansi) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')

        {{-- Section: Data Pelanggan --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            <h2 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user text-blue-500"></i> Data Pelanggan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $garansi->nama) }}" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @error('nama') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">No HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $garansi->no_hp) }}" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @error('no_hp') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Lokasi Chat --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Chat <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(config('whatsapp.lokasi') as $key => $lokasi)
                    <label class="cursor-pointer">
                        <input type="radio" name="lokasi_chat" value="{{ $key }}" {{ old('lokasi_chat', $garansi->lokasi_chat) === $key ? 'checked' : '' }} class="peer sr-only" required>
                        <div class="border-2 border-slate-200 rounded-xl p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-colors">
                            <i class="fab fa-whatsapp text-2xl text-emerald-500 mb-1"></i>
                            <div class="text-sm font-medium text-slate-900">{{ $lokasi['nama'] }}</div>
                            <div class="text-xs text-slate-400">{{ $lokasi['nomor'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('lokasi_chat') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section: Detail Pembelian --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            <h2 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-500"></i> Detail Pembelian
            </h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Invoice Pembelian</label>
                <div class="flex gap-2">
                    <input type="text" name="invoice_pembelian" id="invoice_pembelian" value="{{ old('invoice_pembelian', $garansi->invoice_pembelian) }}"
                        class="flex-1 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    <button type="button" id="btn-scrape-invoice"
                        class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 whitespace-nowrap transition-colors">
                        <i class="fas fa-magnifying-glass"></i> Scrape
                    </button>
                </div>
                <p id="scrape-result" class="text-xs mt-1.5 hidden"></p>
            </div>

            {{-- Items --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Barang + Serial Number <span class="text-rose-500">*</span></label>
                <div id="items-container" class="space-y-2"></div>
                <button type="button" onclick="addItem()"
                    class="mt-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-1.5 transition-colors">
                    <i class="fas fa-plus"></i> Tambah Barang
                </button>
                @error('items') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Beli <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_beli" value="{{ old('tanggal_beli', $garansi->tanggal_beli->format('Y-m-d')) }}" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @error('tanggal_beli') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Marketplace <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_marketplace" value="{{ old('nama_marketplace', $garansi->nama_marketplace) }}" required list="marketplace-list"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    <datalist id="marketplace-list">
                        <option value="Shopee"><option value="Tokopedia"><option value="TikTok Shop">
                        <option value="Bukalapak"><option value="Lazada"><option value="Blibli">
                        <option value="Website / Official Store"><option value="Lainnya">
                    </datalist>
                    @error('nama_marketplace') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section: Kondisi Barang --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            <h2 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <i class="fas fa-screwdriver-wrench text-blue-500"></i> Kondisi Barang
            </h2>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kerusakan <span class="text-rose-500">*</span></label>
                <textarea name="kerusakan" rows="3" required class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">{{ old('kerusakan', $garansi->kerusakan) }}</textarea>
                @error('kerusakan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kelengkapan Barang <span class="text-rose-500">*</span></label>
                <textarea name="kelengkapan_barang" rows="2" required class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">{{ old('kelengkapan_barang', $garansi->kelengkapan_barang) }}</textarea>
                @error('kelengkapan_barang') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-1">
            <a href="{{ route('garansi.show', $garansi) }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
                <i class="fas fa-save"></i> Update Data
            </button>
        </div>
    </form>
</div>
@include('bublechat')
@endsection

@push('scripts')
<script>
    let itemIndex = 0;

    function addItem(namaBarang = '', serialNumber = '', id = null) {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-start';

        let idInput = '';
        if (id) {
            idInput = `<input type="hidden" name="items[${itemIndex}][id]" value="${id}">`;
        }

        row.innerHTML = `
            ${idInput}
            <div class="flex-1">
                <input type="text" name="items[${itemIndex}][nama_barang]" value="${namaBarang}" placeholder="Nama Barang"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div class="flex-1">
                <input type="text" name="items[${itemIndex}][serial_number]" value="${serialNumber}" placeholder="Serial Number (SN)"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <button type="button" onclick="removeItem(this)" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-2.5 rounded-xl text-sm font-medium flex-shrink-0 transition-colors" title="Hapus">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(row);
        itemIndex++;
    }

    function removeItem(button) {
        const container = document.getElementById('items-container');
        if (container.children.length <= 1) { alert('Minimal harus ada 1 barang.'); return; }
        button.closest('.flex').remove();
    }

    // Load existing items
    @php
        $oldItems = old('items', $garansi->items->map(fn($i) => ['id' => $i->id, 'nama_barang' => $i->nama_barang, 'serial_number' => $i->serial_number])->toArray());
    @endphp
    @foreach($oldItems as $oldItem)
    addItem('{{ addslashes($oldItem['nama_barang'] ?? '') }}', '{{ addslashes($oldItem['serial_number'] ?? '') }}', {{ $oldItem['id'] ?? 'null' }});
    @endforeach

    // Invoice Scrape (same as create)
    document.getElementById('btn-scrape-invoice')?.addEventListener('click', function() {
        const invoiceNumber = document.getElementById('invoice_pembelian').value.trim();
        if (!invoiceNumber) { alert('Masukkan Invoice Pembelian terlebih dahulu.'); return; }
        const btn = this;
        const resultEl = document.getElementById('scrape-result');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scraping...';
        resultEl.classList.remove('hidden');
        resultEl.className = 'text-xs mt-1.5 text-slate-500';
        resultEl.textContent = 'Sedang scraping...';
        fetch('{{ route("garansi.scrape-invoice") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ invoice_pembelian: invoiceNumber })
        })
        .then(r => r.json())
        .then(data => {
            resultEl.className = 'text-xs mt-1.5 ' + (data.success ? 'text-emerald-600' : 'text-amber-600');
            resultEl.textContent = (data.success ? '✓ ' : '⚠ ') + data.message;
        })
        .catch(e => { resultEl.textContent = 'Error: ' + e.message; })
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-magnifying-glass"></i> Scrape'; });
    });
</script>
@endpush