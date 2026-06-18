@extends('layouts.main')
@section('title', 'Cabang Toko')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Cabang Toko</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola cabang mini market MyFanel.</p>
            </div>

            <button type="button" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Cabang
            </button>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-branch',
            'searchPlaceholder' => 'Cari kode, nama, atau kota cabang...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Bandung', 'column' => 2, 'value' => 'Bandung'],
                ['label' => 'Jakarta', 'column' => 2, 'value' => 'Jakarta'],
                ['label' => 'Aktif', 'column' => 5, 'value' => 'Aktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="branch-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Cabang</th>
                            <th class="px-6 py-4">Kota</th>
                            <th class="px-6 py-4">Telepon</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['BR-001', 'MyFanel Bandung', 'Bandung', '022-1234567', 'Jl. Asia Afrika No. 1', 'Aktif', true],
                            ['BR-002', 'MyFanel Jakarta', 'Jakarta', '021-9876543', 'Jl. Sudirman No. 10', 'Aktif', true],
                        ] as [$kode, $nama, $kota, $telepon, $alamat, $status, $isActive])
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $kode }}</td>
                                <td class="px-6 py-4">{{ $nama }}</td>
                                <td class="px-6 py-4">{{ $kota }}</td>
                                <td class="px-6 py-4">{{ $telepon }}</td>
                                <td class="px-6 py-4">{{ $alamat }}</td>
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
            initMasterDataTable('#branch-table', {
                searchInput: '#search-branch',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
