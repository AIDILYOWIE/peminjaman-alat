<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InventApp - Peminjaman Alat')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assignment+Turn:wght@300;400;500;600;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#f0f3f7] text-gray-900 antialiased font-['Instrument_Sans']">
    <!-- Navbar User (Tokopedia Style) -->
    @include('components.user-navbar')

    <!-- Main Content Area -->
    <main class="pt-32 lg:pt-36 min-h-screen pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer User -->
    @include('components.user-footer')

    @include('components.cart-drawer')

    <script>
        function initCartStore() {
            if (Alpine.store('cart')) return;

            Alpine.store('cart', {
                items: JSON.parse(localStorage.getItem('borrow_basket') || '[]'),
                showDrawer: false,

                add(item) {
                    let existing = this.items.find(i => i.id === item.id);
                    if (existing) {
                        if (existing.qty < item.stock) {
                            existing.qty++;
                            this.showToast('Berhasil menambah jumlah ' + item.nama, 'success');
                        } else {
                            this.showToast('Stok alat ' + item.nama + ' sudah mencapai batas maksimal.', 'error');
                        }
                    } else {
                        this.items.push({
                            id: item.id,
                            nama: item.nama,
                            code: item.code,
                            qty: 1,
                            stock: item.stock,
                            gambar: item.gambar
                        });
                        this.showToast('Alat ' + item.nama + ' ditambahkan ke keranjang.', 'success');
                    }
                    this.save();
                    this.showDrawer = true;
                },

                buyNow(item) {
                    const directData = {
                        id: item.id,
                        nama: item.nama,
                        code: item.code,
                        qty: 1,
                        stock: item.stock,
                        gambar: item.gambar
                    };
                    localStorage.setItem('direct_borrow', JSON.stringify(directData));
                    window.location.href = "{{ route('user.borrow.checkout') }}";
                },

                remove(id) {
                    this.items = this.items.filter(i => i.id !== id);
                    this.save();
                },

                updateQty(id, delta) {
                    let item = this.items.find(i => i.id === id);
                    if (item) {
                        let newQty = item.qty + delta;
                        if (newQty > 0 && newQty <= item.stock) {
                            item.qty = newQty;
                            this.save();
                        }
                    }
                },

                save() {
                    localStorage.setItem('borrow_basket', JSON.stringify(this.items));
                },

                get totalItems() {
                    return this.items.reduce((sum, item) => (sum + (item.qty || 0)), 0);
                },

                checkout() {
                    if (this.items.length === 0) return;
                    window.location.href = "{{ route('user.borrow.checkout') }}";
                },

                showToast(msg, type) {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            message: msg,
                            type: type
                        }
                    }));
                }
            });
        }

        if (window.Alpine) {
            initCartStore();
        } else {
            document.addEventListener('alpine:init', initCartStore);
        }
    </script>

    @stack('scripts')
    <x-toast />
</body>

</html>