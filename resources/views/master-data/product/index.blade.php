@extends('layouts.main')
@section('title', 'Produk')

@php($canManage = auth()->user()->role->canManageProducts())

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

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
                <a href="{{ route('products.create') }}"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Produk
                </a>
            @endif
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-produk',
            'searchPlaceholder' => 'Cari kode, nama, kategori, atau cabang...',
            'branchFilterId' => $canSelectBranch ? 'filter-product-branch' : null,
            'branches' => $branches,
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Aktif', 'column' => 6, 'value' => 'Aktif'],
                ['label' => 'Nonaktif', 'column' => 6, 'value' => 'Nonaktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="product-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Cabang</th>
                            <th class="px-6 py-4">Harga Jual</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Status</th>
                            @if ($canManage)
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $product->code }}</td>
                                <td class="px-6 py-4">{{ $product->name }}</td>
                                <td class="px-6 py-4">{{ $product->category?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $product->branch->name }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 {{ $product->isLowStock() ? 'text-red-500 font-medium' : '' }}">{{ $product->stock }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $product->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                @if ($canManage)
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <a href="{{ route('products.edit', $product) }}" class="btn-action btn-action-edit" title="Edit">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                                    <i class="fas fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
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
        $(function () {
            const options = {
                searchInput: '#search-produk',
                filterButtons: '.table-filter-btn',
            };

            @if ($canSelectBranch)
                options.branchFilter = {
                    select: '#filter-product-branch',
                    column: 3,
                };
            @endif

            initMasterDataTable('#product-table', options);
        });
    </script>
@endpush
