<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Garansi System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if (file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 bg-slate-50 relative overflow-hidden">
            {{-- Decorative background --}}
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl"></div>
            </div>

            <div class="relative w-full sm:max-w-md">
                <a href="/" class="flex items-center justify-center gap-2.5 mb-6 group">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 group-hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-shield-halved text-xl"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">
                        Garansi<span class="text-blue-600">System</span>
                    </span>
                </a>

                <div class="w-full bg-white/90 backdrop-blur-sm shadow-xl shadow-slate-900/5 border border-slate-200/70 rounded-3xl px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>

                <p class="text-center text-xs text-slate-400 mt-6">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Garansi System') }} &middot; Sistem Manajemen Garansi
                </p>
            </div>
        </div>
    </body>
</html>
