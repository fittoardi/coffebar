<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') — CoffeBar</title>

    <!-- Google Fonts: Rustic Modern (Fraunces, DM Sans, Caveat) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,400..700,50&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/bean-single.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F4ECDD] min-h-screen text-[#2B1D14] flex flex-col justify-center items-center py-12 px-4 sm:px-6 font-sans antialiased selection:bg-[#B5753A] selection:text-white relative overflow-hidden">

    <!-- Pinggiran Biji Kopi Layar: 4 Sudut Layar Luar -->
    <img src="{{ asset('images/beans-falling.png') }}" class="fixed left-6 top-8 w-28 h-auto object-contain pointer-events-none opacity-40 -rotate-12 select-none hidden md:block" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="fixed right-10 top-10 w-24 h-24 object-contain pointer-events-none opacity-45 rotate-45 select-none hidden md:block" alt="" />
    <img src="{{ asset('images/beans-falling.png') }}" class="fixed right-6 bottom-8 w-28 h-auto object-contain pointer-events-none opacity-40 rotate-12 select-none hidden md:block" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="fixed left-10 bottom-12 w-24 h-24 object-contain pointer-events-none opacity-40 -rotate-12 select-none hidden md:block" alt="" />

    <div class="w-full max-w-[440px] relative z-10">
        <!-- Brand Header (03-DESIGN.md Section 16.1) -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block group transition">
                <img src="{{ asset('images/bean-single.png') }}" class="w-10 h-10 object-contain mx-auto mb-2 drop-shadow-sm group-hover:rotate-12 transition transform duration-300" alt="Biji Kopi" />
                <span class="font-display font-bold text-3xl tracking-tight text-[#2B1D14] group-hover:text-[#B5753A] transition">
                    COFFEBAR
                </span>
            </a>
            <p class="font-hand text-2xl text-[#B5753A] mt-1">
                "Seduhan hangat, cerita autentik"
            </p>
        </div>

        <!-- Main Card Container (Paper card with Kraft border) -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] shadow-warm rounded-2xl p-7 sm:p-9 relative">
            <img src="{{ asset('images/beans-cluster.png') }}" class="absolute -bottom-5 -right-5 w-20 h-20 object-contain pointer-events-none drop-shadow-sm opacity-80 hidden sm:block z-0" alt="Biji Kopi" />
            @if (session('status'))
            <div class="mb-5 p-3.5 bg-[#E8DCC4] border border-[#CDB48F] text-[#4A3224] text-xs font-semibold rounded-xl">
                {{ session('status') }}
            </div>
            @endif

            @if (session('success'))
            <div class="mb-5 p-3.5 bg-[#7A8467]/15 border border-[#7A8467] text-[#4F5A3E] text-xs font-semibold rounded-xl flex items-center space-x-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-5 p-3.5 bg-[#B4552F]/15 border border-[#B4552F] text-[#B4552F] text-xs font-semibold rounded-xl">
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
        <div class="text-center mt-8 text-xs text-[#6B5646]">
            &copy; {{ date('Y') }} CoffeBar · Rasa Nusantara Diracik Untukmu
        </div>
    </div>

</body>

</html>
