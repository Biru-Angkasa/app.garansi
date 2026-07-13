<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Garansi System') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        <!-- Premium Font: Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if (file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            [x-cloak] { display: none !important; }
            body {
                font-family: 'Outfit', sans-serif !important;
            }
            .text-balance {
                text-wrap: balance;
            }
        </style>
    </head>
    <body class="text-slate-900 antialiased bg-white selection:bg-blue-100 selection:text-blue-900">
        <div class="min-h-screen flex flex-col lg:flex-row">
            
            <!-- Left Pane (Branding/Visuals) - Hidden on mobile, visible on desktop -->
            <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-slate-950 relative flex-col justify-between p-12 xl:p-16 text-white overflow-hidden">
                <!-- Background Image & Atmospheric Gradients -->
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1600132806370-bf17e65e942f?q=80&w=2194&auto=format&fit=crop" alt="Premium Dashboard" class="w-full h-full object-cover opacity-15 mix-blend-luminosity">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/30 to-transparent"></div>
                    <!-- Ambient glows -->
                    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-1/4 right-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
                </div>

                <!-- Content Wrapper z-10 -->
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-3 group w-max">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-600/30 group-hover:scale-105 group-hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-shield-halved text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-white">
                            Garansi<span class="text-blue-400">System</span>
                        </span>
                    </a>

                    <!-- Value Proposition -->
                    <div class="max-w-xl my-auto py-12">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-semibold uppercase tracking-wider mb-6">
                            <i class="fas fa-star"></i> Sistem Terpadu
                        </div>
                        <h1 class="text-4xl xl:text-5xl font-extrabold leading-[1.1] tracking-tight mb-6 text-balance">
                            Kelola layanan garansi Anda dengan lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">profesional</span>.
                        </h1>
                        <p class="text-lg xl:text-xl text-slate-400 leading-relaxed mb-10 max-w-md">
                            Lacak status klaim, otomatisasi notifikasi WhatsApp, dan bangun kepercayaan pelanggan dalam satu dashboard cerdas.
                        </p>
                        
                        <!-- Floating Glassmorphic Trust Widget -->
                        <div class="bg-white/[0.03] backdrop-blur-xl border border-white/10 rounded-2xl p-5 max-w-sm transform hover:-translate-y-1 transition-transform duration-500 shadow-2xl">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 shrink-0">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-medium tracking-wide">AKTIVITAS TERAKHIR</p>
                                    <p class="font-semibold text-slate-100 mt-0.5">Klaim #INV-9824 Selesai</p>
                                </div>
                            </div>
                            <div class="w-full bg-slate-800/50 rounded-full h-1.5 mt-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-1.5 rounded-full w-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Security -->
                    <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                        <i class="fas fa-lock text-slate-400"></i>
                        <span>Sistem diamankan dengan enkripsi tingkat tinggi</span>
                    </div>
                </div>
            </div>

            <!-- Right Pane (Auth Forms) -->
            <div class="w-full lg:w-7/12 xl:w-1/2 flex flex-col justify-center min-h-screen px-6 py-12 lg:px-20 xl:px-32 bg-slate-50 lg:bg-white relative">
                
                <!-- Mobile only logo & Header -->
                <a href="/" class="flex lg:hidden items-center justify-center gap-3 mb-10 group">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-500/25">
                        <i class="fas fa-shield-halved text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">
                        Garansi<span class="text-blue-600">System</span>
                    </span>
                </a>

                <!-- Form Container -->
                <div class="w-full max-w-[420px] mx-auto bg-white/70 lg:bg-transparent backdrop-blur-xl lg:backdrop-blur-none border border-slate-200/60 lg:border-none p-8 lg:p-0 rounded-3xl shadow-xl shadow-slate-200/40 lg:shadow-none">
                    {{ $slot }}
                </div>
                
                <!-- Global Footer for Mobile -->
                <p class="text-center lg:hidden text-xs text-slate-400 mt-8 font-medium">
                    &copy; {{ date('Y') }} Garansi System &middot; Hak Cipta Dilindungi
                </p>
            </div>

        </div>
    </body>
</html>
