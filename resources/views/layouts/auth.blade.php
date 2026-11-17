<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') — CoffeBar</title>

    <!-- Google Fonts: Rustic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="texture-parchment min-h-screen font-body text-rustic-800 flex flex-col justify-center items-center py-10 px-4 sm:px-6">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block hover:opacity-90 transition">
                <span class="text-3xl sm:text-4xl font-display font-black tracking-wide text-rustic-900">
                    ☕ COFFEBAR
                </span>
            </a>
            <div class="text-copper text-sm tracking-widest my-1 select-none">
                ❧ ─────── ✦ ─────── ❧
            </div>
            <p class="font-body italic text-sm text-rustic-700">
                Kedai Kopi & Seduhan Cerita Autentik
            </p>
        </div>

        <!-- Main Card Container (Retro Member Pass) -->
        <div class="bg-parchment-dark border-4 border-rustic-900 shadow-rustic-lg rounded-sm p-6 sm:p-8 relative">
            <div class="absolute -top-3.5 right-6 pointer-events-none select-none">
                <span class="inline-block bg-retro-orange text-parchment font-heading text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 border-2 border-rustic-900 shadow-sm rotate-2">
                    ★ MEMBER PASS ★
                </span>
            </div>
            @if (session('status'))
            <div class="mb-5 p-4 bg-rustic-100 border-l-4 border-copper text-rustic-800 text-sm font-body">
                {{ session('status') }}
            </div>
            @endif

            @if (session('success'))
            <div class="mb-5 p-4 bg-[#2D6A4F]/10 border-l-4 border-[#2D6A4F] text-[#2D6A4F] text-sm font-body">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-5 p-4 bg-brick/10 border-l-4 border-brick text-brick text-sm font-body">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer Notice -->
        <div class="text-center mt-6 text-xs text-rustic-700 font-body">
            &copy; {{ date('Y') }} CoffeBar. Semua hak cipta dilindungi.
        </div>
    </div>

</body>

</html>