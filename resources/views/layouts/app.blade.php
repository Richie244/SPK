<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex bg-gray-100 min-h-screen font-sans antialiased">
    <!-- Sidebar -->
    @unless(request()->routeIs('login', 'register')) <!-- Sidebar hanya ditampilkan jika bukan halaman login -->
        <aside class="w-64 bg-purple-700 text-white flex flex-col">
            <div class="p-4 text-center">
                <h1 class="text-lg font-bold">SPK-SAW</h1>
                <p class="text-sm">Sistem Penilaian Universitas</p>
            </div>
            <nav class="flex-1">
                <ul>
                    <li>
                        <a href="/dashboard" class="block px-4 py-2 hover:bg-purple-800">Dashboard</a>
                    </li>
                    <li>
                        <a href="/range" class="block px-4 py-2 hover:bg-purple-800">Range</a>
                    </li>
                    <li>
                        <a href="/normalisasi" class="block px-4 py-2 hover:bg-purple-800">Normalisasi</a>
                    </li>
                    <li>
                        <a href="/ranking" class="block px-4 py-2 hover:bg-purple-800">Ranking</a>
                    </li>
                    <li>
                        <a href="/data" class="block px-4 py-2 hover:bg-purple-800">Data</a>
                    </li>
                </ul>
            </nav>
            <div class="p-4">
                <a href="/settings" class="block px-4 py-2 hover:bg-purple-800">Settings</a>
                <a href="/logout" class="block px-4 py-2 hover:bg-purple-800">Logout</a>
            </div>
        </aside>
    @endunless

    <!-- Main Content -->
    <div class="flex-1">
        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="">
            @yield('content')
            @yield('hasil') <!-- Use $hasil if it's set, otherwise an empty string -->
             <!-- This allows for additional content to be injected -->
        </main>
    </div>
</body>
</html>
