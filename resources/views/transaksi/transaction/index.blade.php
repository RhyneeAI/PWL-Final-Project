@extends('layouts.main')
@section('title', 'Penjualan')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Penjualan</h1>
                <p class="text-gray-500 dark:text-gray-400">Daftar transaksi penjualan di semua cabang.</p>
            </div>

            <a href="{{ route('transaction.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Transaksi Baru
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-transaction',
            'searchPlaceholder' => 'Cari no transaksi atau pelanggan...',
            'branchFilterId' => $canSelectBranch ? 'filter-transaction-branch' : null,
            'branches' => $branches,
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="transaction-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">No Transaksi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            @if ($canSelectBranch)
                                <th class="px-6 py-4">Cabang</th>
                            @endif
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Kasir</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $trx->code }}</td>
                                <td class="px-6 py-4">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                                @if ($canSelectBranch)
                                    <td class="px-6 py-4">{{ $trx->branch?->name ?? '-' }}</td>
                                @endif
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="badge-{{ $trx->status->badgeColor() }}">{{ $trx->status->label() }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $trx->user?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('transaction.show', $trx) }}"
                                        class="btn-action btn-action-show" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canSelectBranch ? 8 : 7 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada transaksi penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge-emerald { background: #d1fae5; color: #065f46; padding: 2px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-amber { background: #fef3c7; color: #92400e; padding: 2px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-red { background: #fee2e2; color: #991b1b; padding: 2px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            const options = {
                searchInput: '#search-transaction',
                order: [[1, 'desc']],
            };

            @if ($canSelectBranch)
                options.branchFilter = {
                    select: '#filter-transaction-branch',
                    column: 2,
                };
            @endif

            initMasterDataTable('#transaction-table', options);
        });
    </script>
@endpush
