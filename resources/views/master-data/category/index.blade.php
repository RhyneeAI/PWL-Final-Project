@extends('layouts.main')
@section('title', 'Kategori')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Kategori</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola kategori produk yang berlaku untuk semua cabang.</p>
            </div>

            <a href="{{ route('categories.create') }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-category',
            'searchPlaceholder' => 'Cari nama kategori atau deskripsi...',
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
                        @foreach ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                                <td class="px-6 py-4">{{ $category->description ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $category->products_count }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $category->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a href="{{ route('categories.edit', $category) }}" class="btn-action btn-action-edit" title="Edit">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Hapus">
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
            initMasterDataTable('#category-table', {
                searchInput: '#search-category',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
