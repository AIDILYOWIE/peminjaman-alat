<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Peminjaman Alat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen antialiased">
    <div class="min-h-screen flex" x-data="{ role: 'siswa' }">
        <!-- Left Side - Illustration/Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 to-purple-700 p-12 relative overflow-hidden">
            <!-- Background Patterns -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full -ml-36 -mb-36"></div>

            <div class="relative z-10 flex flex-col justify-between w-full">
                <!-- Logo -->
                <div class="flex items-center gap-3 text-white">
                    <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <x-heroicon-o-beaker class="w-7 h-7" />
                    </div>
                    <span class="text-2xl font-bold">InventApp</span>
                </div>

                <!-- Main Illustration Text -->
                <div class="space-y-6">
                    <h1 class="text-5xl font-bold text-white leading-tight">
                        Sistem Peminjaman<br>Alat Digital
                    </h1>
                    <p class="text-indigo-100 text-lg leading-relaxed max-w-md">
                        Platform terpadu untuk mengelola inventaris dan sirkulasi peminjaman alat secara efisien dan tertib.
                    </p>

                    <!-- Features -->
                    <div class="space-y-3 pt-4">
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-heroicon-o-check class="w-4 h-4" />
                            </div>
                            <span class="text-indigo-50">Pengajuan peminjaman mudah & cepat</span>
                        </div>
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-heroicon-o-check class="w-4 h-4" />
                            </div>
                            <span class="text-indigo-50">Tracking real-time status peminjaman</span>
                        </div>
                        <div class="flex items-center gap-3 text-white">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-heroicon-o-check class="w-4 h-4" />
                            </div>
                            <span class="text-indigo-50">Laporan & analitik lengkap</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Quote -->
                <div class="text-indigo-200 text-sm">
                    © 2024 InventApp. Digitalisasi Manajemen Inventaris.
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center gap-3 text-indigo-600 mb-8">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-beaker class="w-6 h-6" />
                    </div>
                    <span class="text-xl font-bold">InventApp</span>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200 border border-gray-100 p-8 md:p-10">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang! 👋</h2>
                        <p class="text-gray-500">Silakan masuk ke akun Anda untuk melanjutkan</p>
                    </div>

                    <!-- Role Selector -->
                    <div class="mb-6">
                        <x-role-selector />
                    </div>

                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-exclamation-circle class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" />
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-800">Terjadi kesalahan:</p>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <x-login-form />

                    <!-- Divider -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">
                            Belum punya akun?
                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Hubungi Admin</a>
                        </p>
                    </div>
                </div>

                <!-- Helper Text -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400">
                        Dengan masuk, Anda menyetujui <a href="#" class="text-indigo-600 hover:underline">Syarat & Ketentuan</a> kami
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>