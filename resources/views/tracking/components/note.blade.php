@if($garansi->catatan)

<div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">

        <div>
            <h2 class="text-lg font-bold text-slate-800">Catatan Teknisi</h2>
            <p class="text-slate-500 text-xs mt-0.5">Informasi tambahan mengenai proses garansi.</p>
        </div>

        <div class="hidden md:flex w-11 h-11 rounded-xl bg-amber-100 text-amber-600 items-center justify-center shrink-0">
            <i class="fa-solid fa-note-sticky"></i>
        </div>

    </div>

    <div class="p-5">

        <div class="rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 p-4">

            <div class="flex items-start gap-3.5">

                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>

                <div class="flex-1">

                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-semibold">CATATAN TEKNISI</span>

                    <div class="mt-2.5 text-slate-700 text-sm leading-6 whitespace-pre-line">
                        {{ $garansi->catatan }}
                    </div>

                </div>

            </div>

        </div>

        <div class="mt-4 flex items-center justify-between border-t pt-3.5 text-xs text-slate-500">

            <div class="flex items-center gap-1.5">
                <i class="fa-regular fa-clock"></i>
                Terakhir diperbarui
            </div>

            <div class="font-semibold text-slate-700">{{ $garansi->updated_at->format('d M Y H:i') }}</div>

        </div>

    </div>

</div>

@endif