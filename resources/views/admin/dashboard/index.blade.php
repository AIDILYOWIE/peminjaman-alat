<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <x-stat-card
        title="Total Alat"
        value="2,543"
        icon="heroicon-o-archive-box"
        color="indigo">
        <span class="text-green-600 font-medium flex items-center gap-1">
            <x-heroicon-o-arrow-trending-up class="w-3 h-3" />
            +12%
        </span>
        <span class="text-gray-400 ml-2">dari bulan lalu</span>
    </x-stat-card>

    <!-- Stat Card 2 -->
    <x-stat-card
        title="Sedang Dipinjam"
        value="45"
        icon="heroicon-o-clock"
        color="orange">
        <span class="text-gray-400">Sedang aktif digunakan</span>
    </x-stat-card>

    <!-- Stat Card 3 -->
    <x-stat-card
        title="Terlambat"
        value="3"
        icon="heroicon-o-exclamation-triangle"
        color="red">
        <span class="text-red-600 font-medium">Perlu tindakan segera</span>
    </x-stat-card>

    <!-- Stat Card 4 -->
    <x-stat-card
        title="Pengembalian Hari Ini"
        value="12"
        icon="heroicon-o-clipboard-document-check"
        color="green">
        <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">Lihat jadwal &rarr;</a>
    </x-stat-card>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- Tren Peminjaman --}}
    <x-card title="Tren Peminjaman" subtitle="30 Hari Terakhir">
        <div id="chart-borrow-trend" class="min-h-[300px]"></div>
    </x-card>

    {{-- Status Ketersediaan --}}
    <x-card title="Status Ketersediaan" subtitle="Total 2,543 Alat">
        <div id="chart-availability" class="min-h-[300px]"></div>
    </x-card>

    {{-- Top 5 Alat --}}
    <x-card title="Top 5 Alat Terpopuler" subtitle="Bulan Ini">
        <div id="chart-top-items" class="min-h-[300px]"></div>
    </x-card>

    {{-- Performa Pengembalian --}}
    <x-card title="Performa Pengembalian" subtitle="Total Transaksi">
        <div id="chart-returns" class="min-h-[300px]"></div>
    </x-card>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity / Items Table -->
    <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Aktivitas Peminjam</h2>
            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua</button>
        </div>

        @php
        $columns = [
        ['label' => 'Peminjam', 'key' => 'user', 'type' => 'avatar'],
        ['label' => 'Alat', 'key' => 'item', 'type' => 'text'],
        ['label' => 'Status', 'key' => 'status', 'type' => 'badge', 'badge_color_key' => 'color'],
        ['label' => 'Tanggal', 'key' => 'date', 'type' => 'date'],
        ];

        $activities = [
        ['user' => 'Arif Satrio', 'item' => 'Sony Alpha a7 III', 'status' => 'Dipinjam', 'color' => 'blue', 'date' => '2 jam lalu'],
        ['user' => 'Sarah Wijaya', 'item' => 'Tripod Manfrotto', 'status' => 'Dikembalikan', 'color' => 'green', 'date' => '5 jam lalu'],
        ['user' => 'Budi Santoso', 'item' => 'Lensa Canon Visual 50mm', 'status' => 'Terlambat', 'color' => 'red', 'date' => '1 hari lalu'],
        ['user' => 'Dian Sastro', 'item' => 'Zoom H6 Recorder', 'status' => 'Menunggu', 'color' => 'yellow', 'date' => 'Baru saja'],
        ];
        @endphp

        <x-data-table :columns="$columns" :rows="$activities" emptyMessage="Saat ini belum ada aktivitas peminjaman." />
    </div>

    <!-- Recent Activity / Items Table -->
    <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h2>
            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua</button>
        </div>

        @php
        $columns = [
        ['label' => 'User', 'key' => 'user', 'type' => 'avatar'],
        ['label' => 'Aksi', 'key' => 'aksi', 'type' => 'badge', 'badge_color_key' => 'color'],
        ['label' => 'Deskripsi', 'key' => 'description', 'type' => 'text'],
        ['label' => 'Tanggal', 'key' => 'date', 'type' => 'date-time'],
        ];

        $activities = [
        ['user' => 'Arif Satrio', 'aksi' => 'CREATE', 'color' => 'green', 'description' => 'Add data peminjaman', 'date' => '12-10-2026 07:00'],
        ['user' => 'Sarah Wijaya', 'aksi' => 'UPDATED', 'color' => 'blue', 'description' => 'Update data peminjaman', 'date' => '20-10-2026 06:00'],
        ['user' => 'Budi Santoso', 'aksi' => 'CREATE', 'color' => 'green', 'description' => 'Add data peminjaman', 'date' => '21-10-2026 15:00'],
        ['user' => 'Dian Sastro', 'aksi' => 'APPROVE', 'color' => 'green', 'description' => 'Menyetujui pinjaman dari Budi Santoso', 'date' => '22-10-2026 13:00'],
        ];
        @endphp

        <x-data-table :columns="$columns" :rows="$activities" emptyMessage="Saat ini belum ada aktivitas peminjaman." />
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // 1. Tren Peminjaman
    new ApexCharts(document.querySelector("#chart-borrow-trend"), {
        series: [{
            name: 'Peminjaman',
            data: [31, 40, 28, 51, 42, 109, 100, 120, 80, 95, 110, 85, 90, 100, 115, 125, 140, 130, 120, 110, 105, 120, 130, 145, 160, 150, 140, 135, 125, 130]
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#4f46e5'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.0,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: [...Array(30).keys()].map(i => `${i+1} Jan`),
            labels: {
                show: false
            }
        },
        yaxis: {
            labels: {
                show: true
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        }
    }).render();

    // 2. Status Ketersediaan
    new ApexCharts(document.querySelector("#chart-availability"), {
        series: [1850, 45, 120, 30],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Tersedia', 'Dipinjam', 'Maintenance', 'Rusak'],
        colors: ['#4f46e5', '#f97316', '#eab308', '#ef4444'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%'
                }
            }
        },
        dataLabels: {
            enabled: false
        }
    }).render();

    // 3. Top 5 Alat
    new ApexCharts(document.querySelector("#chart-top-items"), {
        series: [{
            data: [400, 380, 350, 320, 280]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#4f46e5'],
        xaxis: {
            categories: ['Sony Alpha a7 III', 'Tripod Manfrotto', 'Zoom H6', 'Canon 5D IV', 'LED Panel'],
        },
        grid: {
            strokeDashArray: 4
        }
    }).render();

    // 4. Performa Pengembalian
    new ApexCharts(document.querySelector("#chart-returns"), {
        series: [85, 15],
        chart: {
            type: 'pie',
            height: 300
        },
        style: {
            fontFamily: 'Inter, sans-serif'
        },
        labels: ['Tepat Waktu', 'Terlambat'],
        colors: ['#22c55e', '#ef4444'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val + "%"
            }
        }
    }).render();
</script>
@endpush