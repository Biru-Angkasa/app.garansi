<div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

        <div>
            <h2 class="text-lg font-bold text-slate-800">Produk Garansi</h2>
            <p class="text-slate-500 text-xs mt-0.5">Daftar barang yang sedang diproses.</p>
        </div>

        <div class="hidden md:flex w-11 h-11 rounded-xl bg-indigo-100 text-indigo-600 items-center justify-center shrink-0">
            <i class="fa-solid fa-box-open"></i>
        </div>

    </div>

    <div class="p-5">

        @foreach($garansi->items as $item)

        <div class="group rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all duration-300 p-4 mb-3 last:mb-0">

            <div class="grid lg:grid-cols-12 gap-4 items-center">

                {{-- Thumbnail --}}
                <div class="lg:col-span-2">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center mx-auto group-hover:scale-105 transition">
                        <i class="fa-solid fa-box text-2xl text-blue-600"></i>
                    </div>
                </div>

                {{-- Info --}}
                <div class="lg:col-span-5">

                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-semibold">Produk</span>

                    <h3 class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition mt-1.5">
                        {{ $item->nama_barang }}
                    </h3>

                    <div class="mt-2.5">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400">Serial Number Lama</div>
                        <div class="mt-1 font-mono text-xs bg-slate-100 rounded-lg px-2.5 py-1.5 inline-flex items-center gap-2">
                            {{ $item->serial_number }}
                        </div>
                    </div>

                </div>

                {{-- SN Baru --}}
                <div class="lg:col-span-5">

                    @if($item->serial_number_baru)

                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] uppercase tracking-wider text-emerald-600">Serial Number Baru</div>
                                    <div class="mt-1 font-mono font-bold text-emerald-700 text-sm break-all">{{ $item->serial_number_baru }}</div>
                                </div>
                                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-check text-sm"></i>
                                </div>
                            </div>
                        </div>

                    @else

                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-3.5 h-full flex items-center">
                            <div>
                                <div class="font-semibold text-slate-700 text-sm">Belum Ada Serial Baru</div>
                                <div class="text-xs text-slate-500 mt-0.5">Serial number baru akan muncul setelah proses penggantian selesai.</div>
                            </div>
                        </div>

                    @endif

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>