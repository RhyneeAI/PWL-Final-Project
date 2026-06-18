@extends('layouts.main')
@section('title', 'Kategori')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Kategori</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola kategori produk per cabang.</p>
            </div>

            <button type="button" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </button>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-category',
            'searchPlaceholder' => 'Cari nama kategori...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Aktif', 'column' => 3, 'value' => 'Aktif'],
                ['label' => 'Nonaktif', 'column' => 3, 'value' => 'Nonaktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="category-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4">Jumlah Produk</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['Elektronik', 'Perangkat elektronik dan aksesoris', '24', 'Aktif', true],
                            ['Makanan', 'Makanan dan minuman kemasan', '56', 'Aktif', true],
                            ['Perawatan', 'Produk perawatan tubuh', '18', 'Aktif', true],
                            ['Kebutuhan Rumah', 'Peralatan rumah tangga', '0', 'Nonaktif', false],
                        ] as [$nama, $deskripsi, $jumlah, $status, $isActive])
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $nama }}</td>
                                <td class="px-6 py-4">{{ $deskripsi }}</td>
                                <td class="px-6 py-4">{{ $jumlah }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $isActive ? 'status-badge-active' : 'status-badge-inactive' }}">{{ $status }}</span>
                                </td>
                                @include('partials.master-data.action-buttons')
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
            initMasterDataTable('#category-table', {
                searchInput: '#search-category',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
