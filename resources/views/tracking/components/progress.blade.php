@php

$progress = match($garansi->status){
    'pending' => 16,
    'repair' => 33,
    'replace' => 50,
    'to distribution' => 66,
    'pengiriman' => 83,
    'selesai' => 100,
    default => 0,
};

$statusConfig = match($garansi->status){

    'pending' => [
        'icon'=>'fa-clock',
        'title'=>'Menunggu Antrian',
        'desc'=>'Barang telah diterima dan sedang menunggu antrian teknisi.'
    ],

    'repair' => [
        'icon'=>'fa-screwdriver-wrench',
        'title'=>'Sedang Repair',
        'desc'=>'Barang sedang diperiksa atau diperbaiki oleh teknisi.'
    ],

    'replace' => [
        'icon'=>'fa-arrows-rotate',
        'title'=>'Replace Unit',
        'desc'=>'Produk sedang diproses untuk penggantian unit.'
    ],

    'to distribution' => [
        'icon'=>'fa-box',
        'title'=>'Menuju Distribusi',
        'desc'=>'Barang selesai diproses dan sedang disiapkan ke gudang distribusi.'
    ],

    'pengiriman' => [
        'icon'=>'fa-truck-fast',
        'title'=>'Dalam Pengiriman',
        'desc'=>'Barang sedang dikirim menuju alamat pelanggan.'
    ],

    'selesai' => [
        'icon'=>'fa-circle-check',
        'title'=>'Garansi Selesai',
        'desc'=>'Proses garansi telah selesai.'
    ],

    default => [
        'icon'=>'fa-circle-question',
        'title'=>'Unknown',
        'desc'=>'-'
    ]

};

@endphp

<div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-5 text-white">

        <div class="flex justify-between items-center">

            <div>
                <div class="text-blue-100 text-xs">Progress Garansi</div>
                <div class="text-2xl font-bold mt-1">{{ $progress }}%</div>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid {{ $statusConfig['icon'] }} text-2xl"></i>
            </div>

        </div>

    </div>

    <div class="p-5">

        <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-700" style="width:{{ $progress }}%"></div>
        </div>

        <div class="flex justify-between mt-2 text-xs">
            <span class="text-slate-500">Progress</span>
            <span class="font-bold text-blue-700">{{ $progress }}%</span>
        </div>

        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">

            <div class="flex gap-3.5">

                <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $statusConfig['icon'] }} text-base"></i>
                </div>

                <div>
                    <div class="text-base font-bold text-slate-800">{{ $statusConfig['title'] }}</div>
                    <div class="text-slate-500 text-sm mt-1 leading-6">{{ $statusConfig['desc'] }}</div>
                </div>

            </div>

        </div>

        <div class="grid md:grid-cols-3 gap-3 mt-5">

            <div class="rounded-xl border border-slate-200 p-3.5">
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Status</div>
                <div class="font-bold text-sm mt-1">{{ ucfirst($garansi->status) }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-3.5">
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Update Terakhir</div>
                <div class="font-bold text-sm mt-1">{{ $garansi->updated_at->diffForHumans() }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-3.5">
                <div class="text-[10px] uppercase tracking-wider text-slate-400">Barang Diterima</div>
                <div class="font-bold text-sm mt-1">{{ $garansi->tanggal_sampai->format('d M Y') }}</div>
            </div>

        </div>

    </div>

</div>