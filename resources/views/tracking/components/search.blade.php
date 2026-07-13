<div class="bg-white rounded-2xl shadow-md border border-slate-200 p-5 -mt-4 relative z-20">

    <form method="GET"
          action="{{ route('tracking.index') }}"
          class="space-y-5">

        {{-- SERIAL NUMBER --}}
        <div>

            <div class="flex items-center gap-2.5 mb-3">

                <div
                    class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-barcode text-sm"></i>

                </div>

                <div>

                    <h3 class="font-semibold text-slate-800 text-sm">

                        Cari menggunakan Serial Number

                    </h3>

                    <p class="text-xs text-slate-500">

                        Cara tercepat dan paling akurat.

                    </p>

                </div>

            </div>

            <input
                type="text"
                name="serial_number"
                value="{{ request('serial_number') }}"
                placeholder="Contoh : RK61A123456"

                class="w-full rounded-xl border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:ring-2
                       focus:ring-blue-100
                       focus:border-blue-600
                       outline-none
                       transition">

        </div>

        {{-- ATAU --}}
        <div class="relative">

            <div class="absolute inset-0 flex items-center">

                <div class="w-full border-t border-dashed border-slate-300"></div>

            </div>

            <div class="relative flex justify-center">

                <span
                    class="bg-white px-4 text-xs font-semibold tracking-widest text-slate-400">

                    ATAU

                </span>

            </div>

        </div>

        {{-- NAMA + HP --}}
        <div>

            <div class="flex items-center gap-2.5 mb-3">

                <div
                    class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">

                    <i class="fa-solid fa-user text-sm"></i>

                </div>

                <div>

                    <h3 class="font-semibold text-slate-800 text-sm">

                        Cari menggunakan Nama & Nomor HP

                    </h3>

                    <p class="text-xs text-slate-500">

                        Dipakai apabila Serial Number tidak tersedia.

                    </p>

                </div>

            </div>

            <div class="grid md:grid-cols-2 gap-3">

                <input
                    type="text"
                    name="nama"
                    value="{{ request('nama') }}"
                    placeholder="Nama Customer"

                    class="rounded-xl border border-slate-300
                           px-4 py-2.5 text-sm
                           focus:ring-2
                           focus:ring-blue-100
                           focus:border-blue-600
                           outline-none
                           transition">

                <input
                    type="text"
                    name="no_hp"
                    value="{{ request('no_hp') }}"
                    placeholder="08xxxxxxxxxx"

                    class="rounded-xl border border-slate-300
                           px-4 py-2.5 text-sm
                           focus:ring-2
                           focus:ring-blue-100
                           focus:border-blue-600
                           outline-none
                           transition">

            </div>

        </div>

        {{-- ERROR --}}
        @if(session('tracking_error'))

            <div
                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 flex gap-3">

                <div
                    class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 shrink-0 text-sm">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div>

                    <div class="font-semibold text-red-700 text-sm">

                        Pencarian gagal

                    </div>

                    <div class="text-xs text-red-600 mt-0.5">

                        {{ session('tracking_error') }}

                    </div>

                </div>

            </div>

        @endif

       {{-- BUTTON --}}
        <div class="flex items-center gap-2">

            <button
                type="submit"
                class="flex-1 rounded-xl
                    bg-blue-600
                    hover:bg-blue-700
                    active:scale-[.98]
                    transition
                    py-3
                    text-white
                    font-semibold
                    text-sm">

                <i class="fa-solid fa-magnifying-glass mr-2"></i>

                CEK STATUS GARANSI

            </button>

            @if(request()->filled('serial_number') || request()->filled('nama') || request()->filled('no_hp'))

                <a href="{{ route('tracking.index') }}"
                title="Reset Pencarian"
                class="w-12 h-12 flex items-center justify-center
                        rounded-xl border border-slate-300
                        bg-white hover:bg-slate-100
                        text-slate-600 transition">

                    <i class="fa-solid fa-rotate-left"></i>

                </a>

            @endif

        </div>
        

    </form>

</div>