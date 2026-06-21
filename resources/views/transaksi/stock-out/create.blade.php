@extends('layouts.main')
@section('title', 'Tambah Stok Keluar')

@section('content')
<div class="max-w-6xl mx-auto">
    @include('partials.session-alert')

    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Tambah Stok Keluar</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Catat pengeluaran barang karena rusak, kadaluwarsa, atau keperluan internal.</p>
        </div>

        <form action="{{ route('stock-out.store') }}" method="POST" id="stock-out-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($canSelectBranch)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabang</label>
                        <select id="stock-out-branch-id" name="branch_id"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id', $selectedBranchId) == $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No Transaksi</label>
                    <input type="text" id="stock-out-code-preview" value="{{ $nextCode }}" readonly
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi</label>
                    @include('partials.date-input', [
                        'name' => 'mutation_date',
                        'value' => old('mutation_date', now()->format('Y-m-d')),
                        'required' => true,
                    ])
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Alasan pengeluaran barang (opsional)..."
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Detail Produk</h2>
                    <button type="button" id="stock-out-add-item"
                        class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Baris
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-700">
                    <table class="w-full min-w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 w-36">Qty</th>
                                <th class="px-4 py-3 text-center w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="stock-out-items-body">
                            @php($oldItems = old('items', [['product_id' => '', 'quantity' => '']]))
                            @foreach ($oldItems as $index => $item)
                                @include('transaksi.stock-out.partials.item-row', ['index' => $index, 'item' => $item, 'products' => $products])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('stock-out.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-red-500 text-white hover:bg-red-600">
                    Simpan Stok Keluar
                </button>
            </div>
        </form>
    </div>
</div>

<template id="stock-out-item-template">
    @include('transaksi.stock-out.partials.item-row', ['index' => '__INDEX__', 'item' => ['product_id' => '', 'quantity' => ''], 'products' => $products])
</template>
@endsection

@include('partials.date-picker-assets')

@push('scripts')
    <script>
        window.stockOutCatalog = @json($branchCatalog);
        window.stockOutInitialBranchId = {{ (int) old('branch_id', $selectedBranchId) }};
    </script>
    <script src="/assets/js/formatted-number-input.js"></script>
    <script src="/assets/js/stock-out-form.js"></script>
@endpush
