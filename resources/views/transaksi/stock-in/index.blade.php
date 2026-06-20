@extends('layouts.main')
@section('title', 'Stok Masuk')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Stok Masuk</h1>
                <p class="text-gray-500 dark:text-gray-400">Catat pembelian barang dari supplier dan perbarui stok produk.</p>
            </div>

            <a href="{{ route('stock-mutation.create') }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Stok Masuk
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-stock-in',
            'searchPlaceholder' => 'Cari no transaksi atau supplier...',
            'branchFilterId' => $canSelectBranch ? 'filter-stock-in-branch' : null,
            'branches' => $branches,
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="stock-in-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">No Transaksi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            @if ($canSelectBranch)
                                <th class="px-6 py-4">Cabang</th>
                            @endif
                            <th class="px-6 py-4">Supplier</th>
                            <th class="px-6 py-4">Total Item</th>
                            <th class="px-6 py-4">Total Qty</th>
                            <th class="px-6 py-4">Total Nilai</th>
                            <th class="px-6 py-4">Petugas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockIns as $stockIn)
                            @php
                                $branch = $branchMap->get($stockIn->branch_id);
                                $supplier = $supplierMap->get($stockIn->supplier_id);
                                $actor = $userMap->get($stockIn->user_id);
                            @endphp
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $stockIn->reference_code }}</td>
                                <td class="px-6 py-4">{{ \Illuminate\Support\Carbon::parse($stockIn->mutation_date)->format('d M Y H:i') }}</td>
                                @if ($canSelectBranch)
                                    <td class="px-6 py-4">{{ $branch?->name ?? '-' }}</td>
                                @endif
                                <td class="px-6 py-4">{{ $supplier?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $stockIn->total_items }}</td>
                                <td class="px-6 py-4">{{ number_format($stockIn->total_quantity, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($stockIn->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $actor?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('stock-mutation.show', $stockIn->reference_code) }}"
                                        class="btn-action btn-action-show" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canSelectBranch ? 9 : 8 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data stok masuk.
                                </td>
                            </tr>
                        @endforelse
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
                searchInput: '#search-stock-in',
                order: [[1, 'desc']],
            };

            @if ($canSelectBranch)
                options.branchFilter = {
                    select: '#filter-stock-in-branch',
                    column: 2,
                };
            @endif

            initMasterDataTable('#stock-in-table', options);
        });
    </script>
@endpush
