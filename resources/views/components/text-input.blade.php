@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 rounded-xl px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition shadow-sm']) }}>
