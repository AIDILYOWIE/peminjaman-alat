@extends('layouts.app')

@section('header', 'Data Alat')

@section('content')
<div class="space-y-6">
    @php
    $columns = [
    [
    'label' => 'Info Alat',
    'key' => 'name',
    'component' => 'table-cells.info',
    'map' => ['subtitle' => 'code', 'icon' => 'icon']
    ],
    [
    'label' => 'Kategori',
    'key' => 'category',
    'component' => 'table-cells.badge',
    'map' => ['color' => 'category_color']
    ],
    [
    'label' => 'Stok',
    'key' => 'stock',
    'component' => 'table-cells.progress',
    'map' => ['total' => 'total_stock'],
    'hidden' => 'hidden sm:table-cell'
    ],
    [
    'label' => 'Status',
    'key' => 'status',
    'component' => 'table-cells.badge',
    'map' => ['color' => 'status_color']
    ],
    ];

    $items = [
    [
    'id' => 1,
    'code' => 'CAM-001',
    'name' => 'Sony Alpha a7 III',
    'category' => 'Kamera',
    'category_color' => 'indigo',
    'stock' => 4,
    'total_stock' => 5,
    'status' => 'Tersedia',
    'status_color' => 'green',
    'icon' => 'heroicon-o-camera'
    ],
    [
    'id' => 2,
    'code' => 'ACC-023',
    'name' => 'Tripod Manfrotto',
    'category' => 'Aksesoris',
    'category_color' => 'blue',
    'stock' => 0,
    'total_stock' => 3,
    'status' => 'Kosong',
    'status_color' => 'red',
    'icon' => 'heroicon-o-video-camera'
    ],
    [
    'id' => 3,
    'code' => 'AUD-005',
    'name' => 'Zoom H6 Recorder',
    'category' => 'Audio',
    'category_color' => 'purple',
    'stock' => 1,
    'total_stock' => 1,
    'status' => 'Maintenance',
    'status_color' => 'yellow',
    'icon' => 'heroicon-o-microphone'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$items"
        routePrefix="admin.items"
        paginated="true"
        hasActions="true"
        searchPlaceholder="Cari alat berdasarkan nama atau kode..."
        addButtonText="Tambah Alat"
        :addButtonRoute="route('admin.items.create')"
        hasFilter="true" />

</div>
@endsection