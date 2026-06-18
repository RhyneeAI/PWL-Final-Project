@extends('layouts.main')
@section('title', 'Pengguna')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Pengguna</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola semua pengguna sistem di sini.</p>
            </div>

            <button type="button" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Pengguna
            </button>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-user',
            'searchPlaceholder' => 'Cari nama, username, atau email...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Owner', 'column' => 3, 'value' => 'Owner'],
                ['label' => 'Manager', 'column' => 3, 'value' => 'Manager'],
                ['label' => 'Kasir', 'column' => 3, 'value' => 'Kasir'],
                ['label' => 'Gudang', 'column' => 3, 'value' => 'Gudang'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="user-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['Pak Jayusman', 'owner', 'owner@myfanel.com', 'Owner'],
                            ['Manager Bandung', 'manager', 'manager@myfanel.com', 'Manager'],
                            ['Kasir Bandung', 'kasir', 'kasir@myfanel.com', 'Kasir'],
                            ['Gudang Bandung', 'gudang', 'gudang@myfanel.com', 'Gudang'],
                        ] as [$nama, $username, $email, $role])
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $nama }}</td>
                                <td class="px-6 py-4">{{ $username }}</td>
                                <td class="px-6 py-4">{{ $email }}</td>
                                <td class="px-6 py-4">{{ $role }}</td>
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
            initMasterDataTable('#user-table', {
                searchInput: '#search-user',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
