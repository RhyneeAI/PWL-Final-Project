@extends('layouts.main')
@section('title', 'Produk')

@php($canManage = auth()->user()->role->canManageProducts())

@section('content')
    <div class="space-y-6 min-h-full">
        <!-- Header -->
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
                <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                    + Tambah Produk
                </button>
            @endif
        </div>

        <!-- Search -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <input type="text" id="search-produk" placeholder="Cari produk..."
                class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">

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

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-white">
                        @foreach ([
                            ['Laptop ASUS', 'Rp 12.000.000', '10', 'Elektronik'],
                            ['Mouse Logitech', 'Rp 250.000', '50', 'Aksesoris'],
                            ['Wireless Headphone', 'Rp 1.250.070', '30', 'Elektronik'],
                            ['Smart Watch Series 8', 'Rp 790.060', '20', 'Wearable'],
                            ['Mechanical Keyboard', 'Rp 80.270', '40', 'Gaming'],
                            ['Monitor Xiaomi G24i 144Hz', 'Rp 1.619.900', '15', 'Elektronik'],
                        ] as [$nama, $harga, $stok, $kategori])
                            <tr class="product-row hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-5">{{ $nama }}</td>
                                <td class="px-6 py-5">{{ $harga }}</td>
                                <td class="px-6 py-5">{{ $stok }}</td>
                                <td class="px-6 py-5">{{ $kategori }}</td>
                                @if ($canManage)
                                    <td class="px-6 py-5 text-center space-x-2">
                                        <button class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs">Edit</button>
                                        <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                                    </td>
                                @endif
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
        $('#search-produk').on('keyup', function() {
            let value = $(this).val().toLowerCase();

            $('.product-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
@endpush
