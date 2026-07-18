@if($garansi->catatan)
<div class="bg-amber-50 rounded-2xl border border-amber-200/60 p-5">
    <div class="flex gap-3.5">
        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div>
            <h3 class="font-bold text-sm text-amber-900">Catatan dari Teknisi</h3>
            <div class="text-sm text-amber-800 mt-1.5 whitespace-pre-wrap leading-relaxed">{{ $garansi->catatan }}</div>
        </div>
    </div>
</div>
@endif
