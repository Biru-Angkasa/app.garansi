@extends('layouts.app')
@section('title', 'Buat Data Garansi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Buat Data Garansi</h1>
            <p class="text-sm text-slate-500 mt-0.5">Isi detail klaim garansi dari pelanggan</p>
        </div>
        <a href="{{ route('garansi.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-medium flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('garansi.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Section: Data Pelanggan --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            <h2 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user text-blue-500"></i> Data Pelanggan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @error('nama') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">No HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @error('no_hp') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Lokasi Chat --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Chat (WhatsApp CS) <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($lokasiList as $key => $lokasi)
                    <label class="cursor-pointer">
                        <input type="radio" name="lokasi_chat" value="{{ $key }}" {{ old('lokasi_chat') === $key ? 'checked' : '' }}
                            class="peer sr-only" required>
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

            {{-- Scrape Data Odoo --}}
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Tarik Data Otomatis (Scrape Odoo)</label>
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="w-full md:w-1/4">
                        <select id="odoo-instance" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-violet-500 bg-white outline-none">
                            <option value="">-- Pilih Cabang Odoo --</option>
                            @foreach(config('odoo.instances') as $key => $instance)
                            <option value="{{ $key }}">{{ $instance['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex-1 flex gap-2">
                        <input type="text" name="invoice_pembelian" id="invoice_pembelian" value="{{ old('invoice_pembelian') }}" placeholder="No. Invoice..." class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <button type="button" id="btn-scrape-invoice" class="bg-violet-600 hover:bg-violet-700 text-white px-3 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors"><i class="fas fa-search"></i> Scrape Invoice</button>
                    </div>
                    
                    <div class="flex justify-center md:flex items-center text-slate-400 text-xs font-medium uppercase my-1 md:my-0">Atau</div>

                    <div class="flex-1 flex gap-2">
                        <input type="text" id="search_sn" placeholder="No. Serial Number..." class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <button type="button" id="btn-scrape-sn" class="bg-fuchsia-600 hover:bg-fuchsia-700 text-white px-3 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors"><i class="fas fa-barcode"></i> Scrape SN</button>
                    </div>
                </div>
                <p id="scrape-result" class="text-xs hidden mt-2"></p>
                @error('invoice_pembelian') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Nama Barang + SN (dynamic) --}}
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
                    <input type="date" name="tanggal_beli" value="{{ old('tanggal_beli') }}" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @error('tanggal_beli') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Marketplace <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_marketplace" value="{{ old('nama_marketplace') }}" required list="marketplace-list"
                        placeholder="Shopee / Tokopedia / TikTok Shop / dll"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
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
                <textarea name="kerusakan" rows="3" required
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Jelaskan kerusakan barang...">{{ old('kerusakan') }}</textarea>
                @error('kerusakan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kelengkapan Barang <span class="text-rose-500">*</span></label>
                <textarea name="kelengkapan_barang" rows="2" required
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Box, kabel, adaptor, remote, dll">{{ old('kelengkapan_barang') }}</textarea>
                @error('kelengkapan_barang') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Info: Tanggal Sampai otomatis --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-3 text-sm text-blue-700">
            <i class="fas fa-circle-info"></i>
            <span><strong>Tanggal Sampai</strong> akan otomatis terisi dengan timestamp saat data ini dibuat.</span>
        </div>

        {{-- Submit --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-1">
            <a href="{{ route('garansi.index') }}" class="flex-1 sm:flex-none justify-center text-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                Batal
            </a>
            <button type="submit" class="flex-1 sm:flex-none justify-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
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
        row.className = 'flex flex-col sm:flex-row gap-2 items-start sm:items-center w-full relative bg-slate-50 sm:bg-transparent p-3 sm:p-0 rounded-xl border border-slate-100 sm:border-none';
        row.dataset.itemIndex = itemIndex;
        row.innerHTML = `
            <div class="flex-1 w-full">
                <input type="text" name="items[${itemIndex}][nama_barang]" value="${namaBarang}"
                    placeholder="Nama Barang"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div class="flex-1 w-full">
                <input type="text" name="items[${itemIndex}][serial_number]" value="${serialNumber}"
                    placeholder="Serial Number (SN)"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <button type="button" onclick="removeItem(this)"
                class="absolute sm:static top-3 right-3 sm:top-auto sm:right-auto bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-2.5 rounded-xl text-sm font-medium flex-shrink-0 transition-colors"
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

    // ========== Invoice Scraping dari Odoo ERP ==========
    document.getElementById('btn-scrape-invoice').addEventListener('click', function() {
        const invoiceNumber = document.getElementById('invoice_pembelian').value.trim();
        const odooInstance = document.getElementById('odoo-instance').value;

        if (!invoiceNumber) {
            alert('Masukkan Invoice Pembelian terlebih dahulu.');
            return;
        }
        if (!odooInstance) {
            alert('Pilih instance Odoo terlebih dahulu.');
            return;
        }

        const btn = this;
        const resultEl = document.getElementById('scrape-result');
        const originalHTML = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scraping...';
        resultEl.className = 'text-xs mt-1.5 text-slate-500';
        resultEl.textContent = 'Sedang scraping data dari Odoo...';
        resultEl.classList.remove('hidden');

        fetch('{{ route("garansi.scrape-invoice") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                invoice_pembelian: invoiceNumber,
                odoo_instance: odooInstance,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data) {
                resultEl.className = 'text-xs mt-1.5 text-emerald-600';
                resultEl.textContent = '✓ ' + data.message;

                // Auto-fill items
                if (data.data.items && data.data.items.length > 0) {
                    // Clear existing items
                    document.getElementById('items-container').innerHTML = '';
                    itemIndex = 0;
                    // Add scraped items
                    data.data.items.forEach(item => {
                        addItem(item.nama_barang || '', item.serial_number || '');
                    });
                }

                // Auto-fill tanggal beli
                if (data.data.tanggal_beli) {
                    document.querySelector('input[name="tanggal_beli"]').value = data.data.tanggal_beli;
                }

                // Auto-fill marketplace
                if (data.data.nama_marketplace) {
                    document.querySelector('input[name="nama_marketplace"]').value = data.data.nama_marketplace;
                }
            } else {
                resultEl.className = 'text-xs mt-1.5 text-amber-600';
                resultEl.textContent = '⚠ ' + (data.message || 'Data tidak ditemukan.');
            }
        })
        .catch(err => {
            resultEl.className = 'text-xs mt-1.5 text-rose-500';
            resultEl.textContent = 'Error: ' + err.message;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    });

    // ========== SN Scraping dari Odoo ERP ==========
    document.getElementById('btn-scrape-sn').addEventListener('click', function() {
        const serialNumber = document.getElementById('search_sn').value.trim();
        const odooInstance = document.getElementById('odoo-instance').value;

        if (!serialNumber) {
            alert('Masukkan Serial Number (SN) terlebih dahulu.');
            return;
        }
        if (!odooInstance) {
            alert('Pilih cabang Odoo terlebih dahulu.');
            return;
        }

        const btn = this;
        const resultEl = document.getElementById('scrape-result');
        const originalHTML = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scraping...';
        resultEl.className = 'text-xs mt-1.5 text-slate-500';
        resultEl.textContent = 'Mencari SN di Odoo...';
        resultEl.classList.remove('hidden');

        fetch('{{ route("garansi.scrape-sn") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                serial_number: serialNumber,
                odoo_instance: odooInstance
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;

            if (!data.success && data.error) {
                resultEl.textContent = data.error;
                resultEl.className = 'text-xs mt-1.5 text-rose-500';
                alert(data.error);
                return;
            }
            if (!data.success) {
                resultEl.textContent = data.message || 'Gagal menarik data dari Odoo.';
                resultEl.className = 'text-xs mt-1.5 text-rose-500';
                return;
            }

            resultEl.textContent = data.message;
            resultEl.className = 'text-xs mt-1.5 text-emerald-600';

            // Auto fill data
            const order = data.data;
            if (order.invoice_or_order_number) {
                document.getElementById('invoice_pembelian').value = order.invoice_or_order_number;
            }
            if (order.tanggal_beli) {
                document.querySelector('input[name="tanggal_beli"]').value = order.tanggal_beli;
            }
            if (order.nama_marketplace) {
                document.querySelector('input[name="nama_marketplace"]').value = order.nama_marketplace;
            }

            // Auto fill items
            if (order.items && order.items.length > 0) {
                document.getElementById('items-container').innerHTML = '';
                itemIndex = 0;
                
                order.items.forEach(item => {
                    addItem(item.nama_barang, item.serial_number);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            resultEl.textContent = 'Terjadi kesalahan sistem saat menghubungi Odoo.';
            resultEl.className = 'text-xs mt-1.5 text-rose-500';
        });
    });
</script>
@endpush