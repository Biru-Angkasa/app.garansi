@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs text-rose-500 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5"><i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}</li>
        @endforeach
    </ul>
@endif
