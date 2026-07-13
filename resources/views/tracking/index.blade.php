<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Garansi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-blue-50 min-h-screen">

<div class="max-w-4xl mx-auto px-4 py-6">

    @include('tracking.components.hero')

    @include('tracking.components.search')

    @if($hasSearch && !session('tracking_error'))
        <div class="space-y-5 mt-6">
            @forelse($garansis as $garansi)
            @include('tracking.components.info')
            @include('tracking.components.items')
                <!-- @include('tracking.components.progress') -->
                @include('tracking.components.timeline')
                @include('tracking.components.note')
            @empty
                <div class="bg-white rounded-2xl shadow border border-slate-200 p-10 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h2 class="text-xl font-bold mt-4 text-slate-800">Data Tidak Ditemukan</h2>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                        Pastikan Serial Number atau Nama dan Nomor HP Anda sudah benar.
                    </p>
                </div>
            @endforelse
        </div>
    @endif

</div>

</body>
</html>