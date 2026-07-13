@php

$steps = [

    ['key' => 'pending', 'title' => 'Barang Diterima', 'desc' => 'Barang telah diterima oleh tim garansi.', 'icon' => 'fa-box-open'],
    ['key' => 'repair', 'title' => 'Pemeriksaan / Repair', 'desc' => 'Teknisi sedang memeriksa atau memperbaiki produk.', 'icon' => 'fa-screwdriver-wrench'],
    ['key' => 'replace', 'title' => 'Penggantian Unit', 'desc' => 'Barang sedang diproses untuk penggantian unit.', 'icon' => 'fa-arrows-rotate'],
    ['key' => 'to distribution', 'title' => 'Distribusi', 'desc' => 'Barang telah selesai diproses dan siap dikirim.', 'icon' => 'fa-box'],
    ['key' => 'pengiriman', 'title' => 'Pengiriman', 'desc' => 'Barang sedang menuju alamat pelanggan.', 'icon' => 'fa-truck-fast'],
    ['key' => 'selesai', 'title' => 'Garansi Selesai', 'desc' => 'Proses garansi telah selesai.', 'icon' => 'fa-circle-check'],

];

$current = collect($steps)->pluck('key')->search($garansi->status);

@endphp

<div class="bg-white rounded-2xl border border-slate-200 shadow-md p-5">

    <div class="flex items-center justify-between mb-5">

        <div>
            <h2 class="text-lg font-bold text-slate-800">Timeline Garansi</h2>
            <p class="text-slate-500 text-xs mt-0.5">Pantau proses garansi dari awal hingga selesai.</p>
        </div>

        <div class="hidden md:flex w-11 h-11 rounded-xl bg-blue-100 items-center justify-center text-blue-600 shrink-0">
            <i class="fa-solid fa-route"></i>
        </div>

    </div>

    <div class="space-y-1">

        @foreach($steps as $index => $step)

            @php
                $done = $index < $current;
                $active = $index == $current;
            @endphp

            <div class="flex gap-3.5">

                {{-- ICON --}}
                <div class="flex flex-col items-center">

                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shrink-0
                        {{ $done ? 'bg-green-500 text-white' : '' }}
                        {{ $active ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : '' }}
                        {{ (!$done && !$active) ? 'bg-slate-200 text-slate-400' : '' }}
                        text-sm">

                        @if($done)
                            <i class="fa-solid fa-check"></i>
                        @else
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        @endif

                    </div>

                    @if(!$loop->last)
                        <div class="w-0.5 h-12 {{ ($done || $active) ? 'bg-blue-500' : 'bg-slate-200' }}"></div>
                    @endif

                </div>

                {{-- CONTENT --}}
                <div class="flex-1 pb-5">

                    <div class="rounded-xl border transition-all duration-500
                        {{ $active ? 'border-blue-500 bg-blue-50 shadow-sm' : '' }}
                        {{ $done ? 'border-green-200 bg-green-50' : '' }}
                        {{ (!$done && !$active) ? 'border-slate-200 bg-white' : '' }}
                        p-3.5">

                        <div class="flex justify-between items-center">

                            <h3 class="font-bold text-sm
                                {{ $active ? 'text-blue-700' : '' }}
                                {{ $done ? 'text-green-700' : '' }}
                                {{ (!$done && !$active) ? 'text-slate-700' : '' }}">
                                {{ $step['title'] }}
                            </h3>

                            @if($active)
                                <span class="px-2 py-0.5 rounded-full bg-blue-600 text-white text-[10px] font-semibold shrink-0">Sedang Diproses</span>
                            @elseif($done)
                                <span class="px-2 py-0.5 rounded-full bg-green-600 text-white text-[10px] font-semibold shrink-0">Selesai</span>
                            @endif

                        </div>

                        <p class="text-slate-500 text-xs mt-1 leading-5">{{ $step['desc'] }}</p>

                        @if($active)
                            <div class="mt-2 flex items-center gap-1.5 text-xs text-blue-700">
                                <i class="fa-regular fa-clock"></i>
                                Update terakhir
                                <strong>{{ $garansi->updated_at->diffForHumans() }}</strong>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>