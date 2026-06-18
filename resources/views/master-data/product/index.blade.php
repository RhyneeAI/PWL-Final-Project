@extends('layouts.main')
@section('title', 'Produk')

@php($canManage = auth()->user()->role->canManageProducts())

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Produk</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    @if ($canManage)
                        Kelola semua produk di sini.
                    @else
                        Lihat daftar produk dan stok tersedia.
                    @endif
                </p>
            </div>

            @if ($canManage)
                <button type="button" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Produk
                </button>
            @endif
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-produk',
            'searchPlaceholder' => 'Cari produk, kategori, atau harga...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Elektronik', 'column' => 3, 'value' => 'Elektronik'],
                ['label' => 'Gaming', 'column' => 3, 'value' => 'Gaming'],
                ['label' => 'Stok Menipis', 'column' => 2, 'value' => '5'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="product-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Kategori</th>
                            @if ($canManage)
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['Laptop ASUS', 'Rp 12.000.000', '10', 'Elektronik'],
                            ['Mouse Logitech', 'Rp 250.000', '50', 'Aksesoris'],
                            ['Wireless Headphone', 'Rp 1.250.070', '30', 'Elektronik'],
                            ['Smart Watch Series 8', 'Rp 790.060', '20', 'Wearable'],
                            ['Mechanical Keyboard', 'Rp 80.270', '40', 'Gaming'],
                            ['Monitor Xiaomi G24i 144Hz', 'Rp 1.619.900', '15', 'Elektronik'],
                            ['USB-C Hub', 'Rp 150.000', '5', 'Aksesoris'],
                        ] as [$nama, $harga, $stok, $kategori])
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $nama }}</td>
                                <td class="px-6 py-4">{{ $harga }}</td>
                                <td class="px-6 py-4">{{ $stok }}</td>
                                <td class="px-6 py-4">{{ $kategori }}</td>
                                @include('partials.master-data.action-buttons', ['show' => $canManage])
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            initMasterDataTable('#product-table', {
                searchInput: '#search-produk',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
