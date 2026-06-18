@extends('layouts.main')
@section('title', 'Supplier')

@php($canManage = auth()->user()->role->canManageSuppliers())

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Supplier</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    @if ($canManage)
                        Kelola data supplier untuk stok masuk barang.
                    @else
                        Lihat daftar supplier yang tersedia untuk stok masuk.
                    @endif
                </p>
            </div>

            @if ($canManage)
                <a href="{{ route('supplier.create') }}"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Supplier
                </a>
            @endif
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-supplier',
            'searchPlaceholder' => 'Cari kode, nama, atau email supplier...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Aktif', 'column' => 4, 'value' => 'Aktif'],
                ['label' => 'Nonaktif', 'column' => 4, 'value' => 'Nonaktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="supplier-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Supplier</th>
                            <th class="px-6 py-4">Telepon</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Status</th>
                            @if ($canManage)
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $supplier->code }}</td>
                        <td class="px-6 py-4">{{ $supplier->name }}</td>
                        <td class="px-6 py-4">{{ $supplier->phone }}</td>
                        <td class="px-6 py-4">{{ $supplier->email }}</td>

                        <td class="px-6 py-4">
                            <span class="status-badge {{ $supplier->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                                {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center whitespace-nowrap">
    <div class="inline-flex items-center justify-center gap-2">

        <a href="{{ route('supplier.edit', $supplier->id) }}"
           class="btn-action btn-action-edit">
            <i class="fas fa-pen-to-square"></i>
        </a>

        <form action="{{ route('supplier.destroy', $supplier->id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn-action btn-action-delete">
                <i class="fas fa-trash-can"></i>
            </button>
        </form>

    </div>
</td>
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
            initMasterDataTable('#supplier-table', {
                searchInput: '#search-supplier',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
