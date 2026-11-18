<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') — CoffeBar</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen text-gray-900 flex flex-col justify-center items-center py-10 px-4 sm:px-6 font-sans antialiased">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block hover:opacity-80 transition">
                <span class="text-3xl font-bold tracking-tight text-gray-900">
                    ☕ COFFEBAR
                </span>
            </a>
            <p class="text-xs text-gray-500 mt-1">
                Kedai Kopi & Cerita Autentik
            </p>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6 sm:p-8">
            @if (session('status'))
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 text-xs rounded-md">
                {{ session('status') }}
            </div>
            @endif

            @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 text-xs font-medium rounded-md">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs rounded-md">
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
        <div class="text-center mt-6 text-xs text-gray-400">
            &copy; {{ date('Y') }} CoffeBar. Semua hak cipta dilindungi.
        </div>
    </div>

</body>

</html>
