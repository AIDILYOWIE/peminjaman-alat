<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peminjaman Alat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assignment+Turn:wght@300;400;500;600;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ sidebarCollapsed: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.navbar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen relative overflow-x-hidden">
            <!-- Header -->
            @include('components.header')

            <!-- Page Content -->
            <main class="p-4 lg:p-8 flex-1 bg-gray-50">
                @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 flex items-center gap-3">
                    <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0" />
                    {{ session('success') }}
                </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>
    @stack('scripts')
</body>

</html>