@extends('layouts.main')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-5xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Edit Produk</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Perbarui data produk. Perubahan stok hanya melalui Stok Masuk atau Stok Keluar.</p>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabang</label>
                    @if ($canSelectBranch)
                        <select name="branch_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id', $product->branch_id) == $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input
                            type="text"
                            value="{{ $product->branch->name }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                        >
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Produk</label>
                    <input
                        type="text"
                        value="{{ $product->code }}"
                        readonly
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <select name="category_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Barcode</label>
                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                        placeholder="Opsional"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                        placeholder="Indomie Goreng"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Satuan</label>
                    <select name="unit" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->value }}" @selected(old('unit', $product->unit->value) === $unit->value)>{{ $unit->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="is_active" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        <option value="1" @selected(old('is_active', $product->is_active ? '1' : '0') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active', $product->is_active ? '1' : '0') == '0')>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Beli</label>
                    @include('partials.formatted-number-input', [
                        'name' => 'buy_price',
                        'value' => old('buy_price', $product->buy_price),
                        'prefix' => 'Rp',
                        'placeholder' => '0',
                    ])
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Jual</label>
                    @include('partials.formatted-number-input', [
                        'name' => 'sell_price',
                        'value' => old('sell_price', $product->sell_price),
                        'prefix' => 'Rp',
                        'placeholder' => '0',
                    ])
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stok Saat Ini</label>
                    <input type="text" value="{{ number_format($product->stock, 0, ',', '.') }}" readonly
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stok Minimum</label>
                    @include('partials.formatted-number-input', [
                        'name' => 'min_stock',
                        'value' => old('min_stock', $product->min_stock),
                        'placeholder' => '0',
                    ])
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Batas peringatan stok menipis.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Batal</a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600">Update Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/assets/js/formatted-number-input.js"></script>
@endpush
