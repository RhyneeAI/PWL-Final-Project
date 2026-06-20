@extends('layouts.main')
@section('title', 'Detail Produk')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @include('partials.session-alert')

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Detail Produk</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $product->code }} — {{ $product->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if ($canManage)
                <a href="{{ route('products.edit', $product) }}"
                    class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm">
                    <i class="fas fa-pen-to-square mr-1"></i> Edit
                </a>
            @endif
            <a href="{{ route('products.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Produk</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Kode</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->code }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Nama Produk</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Barcode</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->barcode ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Kategori</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->category?->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Cabang</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->branch->name }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Satuan</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->unit->label() }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Harga Beli</p>
                <p class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Harga Jual</p>
                <p class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Stok Saat Ini</p>
                <p @class([
                    'font-semibold',
                    'text-red-500' => $product->isLowStock(),
                    'text-gray-800 dark:text-white' => ! $product->isLowStock(),
                ])>
                    {{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit->label() }}
                </p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Stok Minimum</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ number_format($product->min_stock, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Status</p>
                <span @class([
                    'status-badge',
                    'status-badge-active' => $product->is_active,
                    'status-badge-inactive' => ! $product->is_active,
                ])>
                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Histori Mutasi Stok</h2>

            <form method="GET" action="{{ route('products.show', $product) }}"
                class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2">
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white">
                <span class="text-gray-400 text-sm text-center sm:px-1">s/d</span>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white">
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm">
                    Filter
                </button>
                @if ($dateFrom || $dateTo)
                    <a href="{{ route('products.show', $product) }}"
                        class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if ($mutations->total() === 0)
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                @if ($dateFrom || $dateTo)
                    Tidak ada mutasi stok pada rentang tanggal yang dipilih.
                @else
                    Belum ada mutasi stok untuk produk ini.
                @endif
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Referensi</th>
                            <th class="px-4 py-3">Perubahan</th>
                            <th class="px-4 py-3">Stok Sebelum</th>
                            <th class="px-4 py-3">Stok Sesudah</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($mutations as $mutation)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $mutation->mutation_date->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'status-badge',
                                        'status-badge-active' => $mutation->isStockIncrease(),
                                        'status-badge-inactive' => ! $mutation->isStockIncrease(),
                                    ])>
                                        {{ $mutation->type->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($mutation->type === \App\Enums\StockMutationType::AdjustIn && $mutation->reference_code)
                                        @if ($canViewStockIn)
                                            <a href="{{ route('stock-mutation.show', $mutation->reference_code) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $mutation->reference_code }}
                                            </a>
                                        @else
                                            {{ $mutation->reference_code }}
                                        @endif
                                    @elseif ($mutation->transaction)
                                        {{ $mutation->transaction->code }}
                                    @elseif ($mutation->reference_code)
                                        {{ $mutation->reference_code }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium {{ $mutation->isStockIncrease() ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $mutation->signedQuantityLabel() }}
                                </td>
                                <td class="px-4 py-3">{{ number_format($mutation->quantity_before, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ number_format($mutation->quantity_after, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if ($mutation->supplier)
                                        Supplier: {{ $mutation->supplier->name }}
                                    @elseif ($mutation->notes)
                                        {{ $mutation->notes }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-gray-100 dark:border-gray-700 pt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $mutations->firstItem() }}–{{ $mutations->lastItem() }} dari {{ $mutations->total() }} mutasi
                </p>
                @include('partials.pagination', ['paginator' => $mutations])
            </div>
        @endif
    </div>
</div>
@endsection
