@extends('layouts.main')
@section('title', 'Detail Stok Keluar')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @include('partials.session-alert')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Detail Stok Keluar</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $referenceCode }}</p>
        </div>
        <a href="{{ route('stock-out.index') }}"
            class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">No Transaksi</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $referenceCode }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Tanggal</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $header->mutation_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Cabang</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $header->branch->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Petugas</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $header->user->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Total Qty Keluar</p>
                <p class="font-semibold text-red-600 dark:text-red-400">{{ number_format($totalQuantity, 0, ',', '.') }}</p>
            </div>
        </div>

        @if ($header->notes)
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Catatan</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $header->notes }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Daftar Produk</h2>
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Qty Keluar</th>
                        <th class="px-4 py-3">Stok Sebelum</th>
                        <th class="px-4 py-3">Stok Sesudah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $item->product->code }}</td>
                            <td class="px-4 py-3">{{ $item->product->name }}</td>
                            <td class="px-4 py-3 text-red-600 dark:text-red-400 font-medium">
                                -{{ number_format(abs($item->quantity_change), 0, ',', '.') }} {{ $item->product->unit->label() }}
                            </td>
                            <td class="px-4 py-3">{{ number_format($item->quantity_before, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ number_format($item->quantity_after, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-800 font-semibold">
                        <td class="px-4 py-3" colspan="2">Total</td>
                        <td class="px-4 py-3 text-red-600 dark:text-red-400">{{ number_format($totalQuantity, 0, ',', '.') }}</td>
                        <td class="px-4 py-3" colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
