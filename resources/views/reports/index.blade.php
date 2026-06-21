@extends('layouts.main')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6 min-h-full">
    @include('partials.session-alert')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Laporan Penjualan</h1>
            <p class="text-gray-500 dark:text-gray-400">Rekap transaksi penjualan per periode.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('report.pdf', request()->query()) }}"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('report.excel', request()->query()) }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('report.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
            </div>
            @if ($canSelectBranch)
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cabang</label>
                    <select name="branch_id"
                        class="rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
                        <option value="">Semua Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected((int) $branchId === $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm">
                    <i class="fas fa-filter mr-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">No Transaksi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        @if ($canSelectBranch)
                            <th class="px-4 py-3">Cabang</th>
                        @endif
                        <th class="px-4 py-3">Kasir</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3 text-right">Harga Jual</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($transactions as $trx)
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50 font-medium">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $trx->code }}</td>
                            <td class="px-4 py-3">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                            @if ($canSelectBranch)
                                <td class="px-4 py-3">{{ $trx->branch->name }}</td>
                            @endif
                            <td class="px-4 py-3">{{ $trx->user->name }}</td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        </tr>
                        @foreach ($trx->items as $item)
                            <tr class="text-sm">
                                <td></td>
                                <td></td>
                                <td></td>
                                @if ($canSelectBranch)
                                    <td></td>
                                @endif
                                <td></td>
                                <td class="px-4 py-2">{{ $item->product_name }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="{{ $canSelectBranch ? 10 : 9 }}" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-800 font-semibold">
                        <td colspan="{{ $canSelectBranch ? 9 : 8 }}" class="px-4 py-3 text-right">Grand Total</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
