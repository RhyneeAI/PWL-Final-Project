@extends('layouts.main')
@section('title', 'Stok Keluar')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Stok Keluar</h1>
                <p class="text-gray-500 dark:text-gray-400">Catat pengeluaran barang karena rusak, kadaluwarsa, atau keperluan internal.</p>
            </div>

            <a href="{{ route('stock-out.create') }}"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Stok Keluar
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-stock-out',
            'searchPlaceholder' => 'Cari no transaksi...',
            'branchFilterId' => $canSelectBranch ? 'filter-stock-out-branch' : null,
            'branches' => $branches,
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="stock-out-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">No Transaksi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            @if ($canSelectBranch)
                                <th class="px-6 py-4">Cabang</th>
                            @endif
                            <th class="px-6 py-4">Total Item</th>
                            <th class="px-6 py-4">Total Qty</th>
                            <th class="px-6 py-4">Petugas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockOuts as $stockOut)
                            @php
                                $branch = $branchMap->get($stockOut->branch_id);
                                $actor = $userMap->get($stockOut->user_id);
                            @endphp
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $stockOut->reference_code }}</td>
                                <td class="px-6 py-4">{{ \Illuminate\Support\Carbon::parse($stockOut->mutation_date)->format('d/m/Y') }}</td>
                                @if ($canSelectBranch)
                                    <td class="px-6 py-4">{{ $branch?->name ?? '-' }}</td>
                                @endif
                                <td class="px-6 py-4">{{ $stockOut->total_items }}</td>
                                <td class="px-6 py-4">{{ number_format($stockOut->total_quantity, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $actor?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('stock-out.show', $stockOut->reference_code) }}"
                                        class="btn-action btn-action-show" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canSelectBranch ? 7 : 6 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data stok keluar.
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
                searchInput: '#search-stock-out',
                order: [[1, 'desc']],
            };

            @if ($canSelectBranch)
                options.branchFilter = {
                    select: '#filter-stock-out-branch',
                    column: 2,
                };
            @endif

            initMasterDataTable('#stock-out-table', options);
        });
    </script>
@endpush
