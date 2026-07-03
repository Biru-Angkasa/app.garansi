@extends('layouts.app')
@section('title', 'Buat Data Garansi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Buat Data Garansi</h1>
        <a href="{{ route('garansi.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('garansi.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf

        {{-- Nama & No HP --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Invoice Pembelian (dengan tombol scraping) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Pembelian</label>
            <div class="flex gap-2">
                <input type="text" name="invoice_pembelian" id="invoice_pembelian" value="{{ old('invoice_pembelian') }}"
                    placeholder="Masukkan Invoice Pembelian..."
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <button type="button" id="btn-scrape-invoice"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 whitespace-nowrap"
                    title="Scrape data Invoice dari marketplace">
                    <i class="fas fa-magnifying-glass"></i> Scrape
                </button>
            </div>
            @error('invoice_pembelian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <p id="scrape-result" class="text-xs mt-1 hidden"></p>
        </div>

        {{-- Nama Barang + SN (dynamic) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang + Serial Number <span class="text-red-500">*</span></label>
            <div id="items-container" class="space-y-2">
                {{-- JS akan inject rows di sini --}}
            </div>
            <button type="button" onclick="addItem()"
                class="mt-2 bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-1">
                <i class="fas fa-plus"></i> Tambah Barang
            </button>
            @error('items') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tanggal Beli & Marketplace --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Beli <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_beli" value="{{ old('tanggal_beli') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('tanggal_beli') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Marketplace <span class="text-red-500">*</span></label>
                <input type="text" name="nama_marketplace" value="{{ old('nama_marketplace') }}" required list="marketplace-list"
                    placeholder="Shopee / Tokopedia / TikTok Shop / dll"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <datalist id="marketplace-list">
                    <option value="EDCCOMP">
                    <option value="FIBRA">
                    <option value="MOBITRA">
                    <option value="CENTRAL">
                    <option value="KABELOS">
                    <option value="MIKROFI">
                    <option value="TARMOC">
                    <option value="Lainnya">
                </datalist>
                @error('nama_marketplace') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Kerusakan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kerusakan <span class="text-red-500">*</span></label>
            <textarea name="kerusakan" rows="3" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="Jelaskan kerusakan barang...">{{ old('kerusakan') }}</textarea>
            @error('kerusakan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kelengkapan Barang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelengkapan Barang <span class="text-red-500">*</span></label>
            <textarea name="kelengkapan_barang" rows="2" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="Box, kabel, adaptor, remote, dll">{{ old('kelengkapan_barang') }}</textarea>
            @error('kelengkapan_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Lokasi Chat --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Chat (WhatsApp CS) <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-3 gap-3">
                @foreach($lokasiList as $key => $lokasi)
                <label class="cursor-pointer">
                    <input type="radio" name="lokasi_chat" value="{{ $key }}" {{ old('lokasi_chat') === $key ? 'checked' : '' }}
                        class="peer sr-only" required>
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

        {{-- Info: Tanggal Sampai otomatis --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center gap-2 text-sm text-blue-700">
            <i class="fas fa-info-circle"></i>
            <span><strong>Tanggal Sampai</strong> akan otomatis terisi dengan timestamp saat data ini dibuat.</span>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-200">
            <a href="{{ route('garansi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Data Garansi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ========== Dynamic Items ==========
    let itemIndex = 0;

    function addItem(namaBarang = '', serialNumber = '') {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-start';
        row.dataset.itemIndex = itemIndex;
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="items[${itemIndex}][nama_barang]" value="${namaBarang}"
                    placeholder="Nama Barang"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div class="flex-1">
                <input type="text" name="items[${itemIndex}][serial_number]" value="${serialNumber}"
                    placeholder="Serial Number (SN)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <button type="button" onclick="removeItem(this)"
                class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-2 rounded-lg text-sm font-medium flex-shrink-0"
                title="Hapus barang ini">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(row);
        itemIndex++;
    }

    function removeItem(button) {
        const container = document.getElementById('items-container');
        if (container.children.length <= 1) {
            alert('Minimal harus ada 1 barang.');
            return;
        }
        button.closest('[data-item-index]').remove();
    }

    // Init: add 1 empty item row (or restore old input)
    @if(old('items'))
        @foreach(old('items') as $oldItem)
        addItem('{{ addslashes($oldItem['nama_barang'] ?? '') }}', '{{ addslashes($oldItem['serial_number'] ?? '') }}');
        @endforeach
    @else
        addItem();
    @endif

    // ========== Invoice Scraping (placeholder) ==========
    document.getElementById('btn-scrape-invoice').addEventListener('click', function() {
        const invoiceNumber = document.getElementById('invoice_pembelian').value.trim();
        if (!invoiceNumber) {
            alert('Masukkan Invoice Pembelian terlebih dahulu.');
            return;
        }

        const btn = this;
        const resultEl = document.getElementById('scrape-result');
        const originalHTML = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scraping...';
        resultEl.className = 'text-xs mt-1 text-gray-500';
        resultEl.textContent = 'Sedang scraping data Invoice...';
        resultEl.classList.remove('hidden');

        fetch('{{ route("garansi.scrape-invoice") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ invoice_pembelian: invoiceNumber })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                resultEl.className = 'text-xs mt-1 text-green-600';
                resultEl.textContent = '✓ ' + data.message;
                // TODO: Isi field otomatis dari hasil scraping
            } else {
                resultEl.className = 'text-xs mt-1 text-orange-500';
                resultEl.textContent = '⚠ ' + data.message;
            }
        })
        .catch(err => {
            resultEl.className = 'text-xs mt-1 text-red-500';
            resultEl.textContent = 'Error: ' + err.message;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    });
</script>
@endpush