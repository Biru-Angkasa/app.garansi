<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 border border-transparent rounded-xl font-medium text-sm text-white shadow-sm shadow-rose-500/20 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:opacity-50 transition-all duration-200']) }}>
    {{ $slot }}
</button>
