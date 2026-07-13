<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Garansi System') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

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
<body class="font-sans antialiased bg-slate-50/50 text-slate-900">
    <div class="min-h-screen flex flex-col">

        <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/75 backdrop-blur-xl border-b border-slate-200/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">

                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                            <div class="relative bg-gradient-to-br from-blue-500 to-blue-700 p-2 rounded-xl text-white shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 group-hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-shield-halved text-base"></i>
                            </div>
                            <span class="text-base font-bold tracking-tight text-slate-900">
                                Garansi<span class="text-blue-600">System</span>
                            </span>
                        </a>

                        <div class="hidden md:flex items-center gap-1 bg-slate-100/70 p-1 rounded-2xl">
                            @php
                                $navItems = [
                                    ['route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Dashboard'],
                                    ['route' => 'garansi.index', 'active' => 'garansi.index*', 'icon' => 'fa-list-check', 'label' => 'Data Garansi'],
                                ];
                            @endphp
                            @foreach($navItems as $item)
                            <a href="{{ route($item['route']) }}"
                               class="relative flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                               {{ request()->routeIs($item['active']) ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                                <i class="fas {{ $item['icon'] }} text-sm {{ request()->routeIs($item['active']) ? 'text-blue-500' : 'text-slate-400' }}"></i>
                                {{ $item['label'] }}
                            </a>
                            @endforeach

                            @if(auth()->user() && auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}"
                               class="relative flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                               {{ request()->routeIs('users.index*') ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                                <i class="fas fa-users-gear text-sm {{ request()->routeIs('users.index*') ? 'text-blue-500' : 'text-slate-400' }}"></i>
                                Manajemen User
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="hidden md:flex items-center gap-3">
                        <a href="{{ route('garansi.create') }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm shadow-blue-500/20 hover:shadow-md hover:shadow-blue-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                            <i class="fas fa-plus text-xs"></i> Buat Garansi
                        </a>

                        <div class="relative border-l border-slate-200 pl-3 h-8 flex items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2 pl-1 pr-2.5 py-1.5 text-sm font-medium rounded-full text-slate-600 hover:bg-slate-100 focus:outline-none transition-colors duration-200">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-full flex items-center justify-center font-semibold text-xs shadow-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                                        <svg class="fill-current h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-slate-700">
                                        <i class="fas fa-id-card text-slate-400 w-4"></i> {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <hr class="border-slate-100 my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt text-red-400 w-4"></i> {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>

                    <div class="-me-2 flex items-center md:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition-colors duration-150">
                            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-t border-slate-100 bg-white shadow-xl">
                <div class="pt-3 pb-3 space-y-1 px-3">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fas fa-gauge-high w-5 text-center"></i> Dashboard
                    </a>
                    <a href="{{ route('garansi.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('garansi.index*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fas fa-list-check w-5 text-center"></i> Data Garansi
                    </a>
                    @if(auth()->user() && auth()->user()->role === 'admin')
                    <a href="{{ route('users.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-base font-medium {{ request()->routeIs('users.index*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fas fa-users-gear w-5 text-center"></i> Manajemen User
                    </a>
                    @endif

                    <div class="pt-2 border-t border-slate-100 my-2">
                        <a href="{{ route('garansi.create') }}"
                           class="flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-xl text-base font-medium shadow-sm">
                            <i class="fas fa-plus"></i> Buat Garansi
                        </a>
                    </div>
                </div>

                <div class="pt-4 pb-4 border-t border-slate-100 bg-slate-50/60 px-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-full flex items-center justify-center font-semibold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-800 text-base leading-tight truncate">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-slate-500 leading-tight truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 text-sm">
                            <i class="fas fa-id-card text-slate-400"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-3 px-3 py-2 text-red-600 rounded-lg hover:bg-red-50 text-sm">
                                <i class="fas fa-sign-out-alt text-red-400"></i> Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="fixed top-20 right-4 sm:right-6 z-[60] max-w-sm w-full">
            <div class="bg-white border border-emerald-200 rounded-2xl p-4 flex items-center gap-3 shadow-xl shadow-emerald-500/10">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <span class="font-medium text-sm text-slate-800 flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition-colors">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="fixed top-20 right-4 sm:right-6 z-[60] max-w-sm w-full">
            <div class="bg-white border border-rose-200 rounded-2xl p-4 flex items-center gap-3 shadow-xl shadow-rose-500/10">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation text-sm"></i>
                </div>
                <span class="font-medium text-sm text-slate-800 flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition-colors">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </div>
        @endif

        <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 flex-1">
            @yield('content', $slot ?? '')
        </main>

        {{-- Global delete confirmation modal --}}
        <div x-data="{ open: false, message: '', form: null }"
             x-cloak
             @confirm-delete.window="open = true; message = $event.detail.message; form = $event.detail.form"
             @keydown.escape.window="open = false">
            <div x-show="open" class="fixed inset-0 z-[70] flex items-center justify-center p-4">
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash-can text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Konfirmasi Hapus</h3>
                    <p class="text-sm text-slate-500 mt-1.5" x-text="message"></p>
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="open = false"
                            class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                            Batal
                        </button>
                        <button type="button" @click="open = false; form && form.submit()"
                            class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm shadow-rose-500/20 transition-colors">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        function confirmDelete(form, message) {
            window.dispatchEvent(new CustomEvent('confirm-delete', { detail: { form: form, message: message } }));
        }
    </script>
    @stack('scripts')
</body>
</html>