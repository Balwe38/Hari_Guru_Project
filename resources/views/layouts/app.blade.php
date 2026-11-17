<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EcoScan AI')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    <header class="bg-green-800 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center justify-between py-4">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold sm:text-xl">♻ EcoScan AI</a>
                <div class="flex gap-5 text-sm font-medium">
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'underline underline-offset-8' : 'opacity-80 hover:opacity-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('scan') }}"
                       class="{{ request()->routeIs('scan*') ? 'underline underline-offset-8' : 'opacity-80 hover:opacity-100' }}">
                        Scan
                    </a>
                </div>
            </nav>

            <div class="pb-6 pt-2 lg:pb-10">
                @yield('header')
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        @yield('content')
    </main>
</body>
</html>