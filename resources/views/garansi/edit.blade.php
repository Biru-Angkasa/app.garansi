@extends('layouts.app')
@section('title', 'Edit Garansi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Garansi</h1>
        <a href="{{ route('garansi.show', $garansi) }}" class="text-gray-600 hover:text-gray-900 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('garansi.update', $garansi) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $garansi->nama) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $garansi->no_hp) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SO Number</label>
            <div class="flex gap-2">
                <input type="text" name="so_number" id="so_number" value="{{ old('so_number', $garansi->so_number) }}"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <button type="button" id="btn-scrape-so"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-magnifying-glass"></i> Scrape
                </button>
            </div>
            <p id="scrape-result" class="text-xs mt-1 hidden"></p>
        </div>

        {{-- Items --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang + Serial Number <span class="text-red-500">*</span></label>
            <div id="items-container" class="space-y-2"></div>
            <button type="button" onclick="addItem()"
                class="mt-2 bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-1">
                <i class="fas fa-plus"></i> Tambah Barang
            </button>
            @error('items') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Beli <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_beli" value="{{ old('tanggal_beli', $garansi->tanggal_beli->format('Y-m-d')) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('tanggal_beli') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marketplace <span class="text-red-500">*</span></label>
                <input type="text" name="nama_marketplace" value="{{ old('nama_marketplace', $garansi->nama_marketplace) }}" required list="marketplace-list"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <datalist id="marketplace-list">
                    <option value="Shopee"><option value="Tokopedia"><option value="TikTok Shop">
                    <option value="Bukalapak"><option value="Lazada"><option value="Blibli">
                    <option value="Website / Official Store"><option value="Lainnya">
                </datalist>
                @error('nama_marketplace') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kerusakan <span class="text-red-500">*</span></label>
            <textarea name="kerusakan" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('kerusakan', $garansi->kerusakan) }}</textarea>
            @error('kerusakan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelengkapan Barang <span class="text-red-500">*</span></label>
            <textarea name="kelengkapan_barang" rows="2" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('kelengkapan_barang', $garansi->kelengkapan_barang) }}</textarea>
            @error('kelengkapan_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Chat <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-3 gap-3">
                @foreach(config('whatsapp.lokasi') as $key => $lokasi)
                <label class="cursor-pointer">
                    <input type="radio" name="lokasi_chat" value="{{ $key }}" {{ old('lokasi_chat', $garansi->lokasi_chat) === $key ? 'checked' : '' }} class="peer sr-only" required>
                    <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-blue-600 peer-checked:bg-blue-50 transition">
                        <i class="fab fa-whatsapp text-2xl text-green-500 mb-1"></i>
                        <div class="text-sm font-medium text-gray-900">{{ $lokasi['nama'] }}</div>
                        <div class="text-xs text-gray-400">{{ $lokasi['nomor'] }}</div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('lokasi_chat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-gray-200">
            <a href="{{ route('garansi.show', $garansi) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <i class="fas fa-save"></i> Update Data
            </button>
        </div>
    </form>
</div>
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
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div class="flex-1">
                <input type="text" name="items[${itemIndex}][serial_number]" value="${serialNumber}" placeholder="Serial Number (SN)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <button type="button" onclick="removeItem(this)" class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-2 rounded-lg text-sm font-medium flex-shrink-0" title="Hapus">
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

    // SO Scrape (same as create)
    document.getElementById('btn-scrape-so')?.addEventListener('click', function() {
        const soNumber = document.getElementById('so_number').value.trim();
        if (!soNumber) { alert('Masukkan SO Number terlebih dahulu.'); return; }
        const btn = this;
        const resultEl = document.getElementById('scrape-result');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scraping...';
        resultEl.classList.remove('hidden');
        resultEl.className = 'text-xs mt-1 text-gray-500';
        resultEl.textContent = 'Sedang scraping...';
        fetch('{{ route("garansi.scrape-so") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ so_number: soNumber })
        })
        .then(r => r.json())
        .then(data => {
            resultEl.className = 'text-xs mt-1 ' + (data.success ? 'text-green-600' : 'text-orange-500');
            resultEl.textContent = (data.success ? '✓ ' : '⚠ ') + data.message;
        })
        .catch(e => { resultEl.textContent = 'Error: ' + e.message; })
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-magnifying-glass"></i> Scrape'; });
    });
</script>
@endpush