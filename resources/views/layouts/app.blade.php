<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EcoScan AI')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="mx-auto min-h-screen max-w-md bg-gray-50 shadow-sm">
        <header class="rounded-b-none bg-green-800 px-4 pb-4 pt-5 text-white">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold">♻ EcoScan AI</a>
            </div>
            @yield('header')
        </header>

        <main class="space-y-4 p-4">
            @yield('content')
        </main>
    </div>
</body>
</html>