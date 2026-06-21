@extends('layouts.main')
@section('title', 'Tambah Stok Masuk')

@section('content')
<div class="max-w-6xl mx-auto">
    @include('partials.session-alert')

    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Tambah Stok Masuk</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Catat pembelian barang dari supplier seperti transaksi pembelian.</p>
        </div>

        <form action="{{ route('stock-mutation.store') }}" method="POST" id="stock-in-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($canSelectBranch)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabang</label>
                        <select id="stock-in-branch-id" name="branch_id"
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
                    <input type="text" id="stock-in-code-preview" value="{{ $nextCode }}" readonly
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                    <select id="stock-in-supplier-id" name="supplier_id" required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        <option value="">— Pilih Supplier —</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan opsional..."
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Detail Produk</h2>
                    <button type="button" id="stock-in-add-item"
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
                                <th class="px-4 py-3 w-44">Harga Beli</th>
                                <th class="px-4 py-3 w-40">Subtotal</th>
                                <th class="px-4 py-3 text-center w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="stock-in-items-body">
                            @php($oldItems = old('items', [['product_id' => '', 'quantity' => '', 'buy_price' => '']]))
                            @foreach ($oldItems as $index => $item)
                                @include('transaksi.stock-in.partials.item-row', ['index' => $index, 'item' => $item, 'products' => $products])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('stock-mutation.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600">
                    Simpan Stok Masuk
                </button>
            </div>
        </form>
    </div>
</div>

<template id="stock-in-item-template">
    @include('transaksi.stock-in.partials.item-row', ['index' => '__INDEX__', 'item' => ['product_id' => '', 'quantity' => '', 'buy_price' => ''], 'products' => $products])
</template>
@endsection

@include('partials.date-picker-assets')

@push('scripts')
    <script>
        window.stockInCatalog = @json($branchCatalog);
        window.stockInInitialBranchId = {{ (int) old('branch_id', $selectedBranchId) }};
    </script>
    <script src="/assets/js/formatted-number-input.js"></script>
    <script src="/assets/js/stock-in-form.js"></script>
@endpush
