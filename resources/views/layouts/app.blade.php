<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peminjaman Alat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assignment+Turn:wght@300;400;500;600;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ sidebarCollapsed: false, sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.navbar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen relative overflow-x-hidden">
            <!-- Header -->
            @include('components.header')

            <!-- Page Content -->
            <main class="p-4 lg:p-8 flex-1 bg-gray-50">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>
    @stack('scripts')
    <x-toast />
    <x-confirm-modal />
</body>

</html>