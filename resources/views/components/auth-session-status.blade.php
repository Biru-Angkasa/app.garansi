@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2.5']) }}>
        <i class="fas fa-check-circle text-emerald-500"></i>
        {{ $status }}
    </div>
@endif
