@php

$statusIcon = match($garansi->status){
    'pending' => 'fa-clock',
    'repair' => 'fa-screwdriver-wrench',
    'replace' => 'fa-arrows-rotate',
    'to distribution' => 'fa-box',
    'pengiriman' => 'fa-truck-fast',
    'selesai' => 'fa-circle-check',
    default => 'fa-circle-question'
};

$progress = match($garansi->status){
    'pending' => 10,
    'repair' => 40,
    'replace' => 40,
    'to distribution' => 40,
    'pengiriman' => 90,
    'selesai' => 100,
    default => 0
};

$statusText = match($garansi->status){
    'pending' => 'Barang telah diterima dan sedang menunggu proses.',
    'repair' => 'Barang sedang diperiksa atau diperbaiki oleh teknisi.',
    'replace' => 'Barang sedang diproses untuk penggantian unit.',
    'to distribution' => 'Barang sedang disiapkan ke bagian distribusi.',
    'pengiriman' => 'Barang sedang dikirim ke alamat pelanggan.',
    'selesai' => 'Proses garansi telah selesai.',
    default => '-'
};

@endphp
<div class="grid lg:grid-cols-3 gap-4">

    {{-- Customer --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

        <div class="flex items-center gap-2.5 mb-4">

            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">

                <i class="fa-solid fa-user text-sm"></i>

            </div>

            <div>

                <h3 class="font-bold text-sm text-slate-800">

                    Informasi Customer

                </h3>

                <p class="text-xs text-slate-500">

                    Data pemilik garansi

                </p>

            </div>

        </div>

        <div class="space-y-3">

            <div>
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Nama</div>
                <div class="font-semibold text-slate-800 text-sm mt-0.5">{{ $garansi->nama }}</div>
            </div>

            <div>
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Nomor HP</div>
                <div class="font-semibold text-slate-800 text-sm mt-0.5">{{ $garansi->no_hp }}</div>
            </div>

            <div>
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Marketplace</div>
                <div class="font-semibold text-slate-800 text-sm mt-0.5">{{ $garansi->nama_marketplace ?: '-' }}</div>
            </div>

        </div>

    </div>

    {{-- Garansi --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

        <div class="flex items-center gap-2.5 mb-4">

            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">

                <i class="fa-solid fa-file-invoice text-sm"></i>

            </div>

            <div>

                <h3 class="font-bold text-sm text-slate-800">

                    Informasi Garansi

                </h3>

                <p class="text-xs text-slate-500">

                    Detail transaksi

                </p>

            </div>

        </div>

        <div class="space-y-3">

            <div>
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Invoice</div>
                <div class="font-semibold text-sm mt-0.5">{{ $garansi->invoice_pembelian ?: '-' }}</div>
            </div>

            <div>
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Tanggal Pembelian</div>
                <div class="font-semibold text-sm mt-0.5">{{ optional($garansi->tanggal_beli)->format('d F Y') ?: '-' }}</div>
            </div>

            <div>
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Lokasi</div>
                <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                    {{ ucfirst($garansi->lokasi_chat) }}
                </span>
            </div>

        </div>

    </div>

    {{-- Status --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl text-white shadow-lg p-5">

        <div class="flex justify-between items-start">

            <div>
                <div class="text-blue-200 text-xs">Status Saat Ini</div>
                <div class="text-xl font-bold mt-1">{{ ucfirst($garansi->status) }}</div>
            </div>

            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $statusIcon }} text-lg"></i>
            </div>

        </div>

        <div class="mt-5">

            <div class="flex justify-between text-xs mb-1.5">
                <span>Progress</span>
                <span class="font-bold">{{ $progress }}%</span>
            </div>

            <div class="w-full h-2 rounded-full bg-white/20 overflow-hidden">
                <div class="h-full rounded-full bg-white transition-all duration-500" style="width:{{ $progress }}%"></div>
            </div>

        </div>

        <div class="mt-4 text-xs leading-6 text-blue-100">
            {{ $statusText }}
        </div>

        <div class="mt-5 pt-4 border-t border-white/20">

            <div class="flex justify-between text-xs">
                <span>Update Terakhir</span>
                <span>{{ $garansi->updated_at->diffForHumans() }}</span>
            </div>

            <div class="flex justify-between text-xs mt-2">
                <span>Barang Diterima</span>
                <span>{{ $garansi->tanggal_sampai->format('d M Y') }}</span>
            </div>

        </div>

    </div>

</div>