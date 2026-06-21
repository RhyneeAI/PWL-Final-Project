@extends('layouts.main')
@section('title', 'Transaksi Baru')

@section('content')
<div class="max-w-full mx-auto px-4">
    @include('partials.session-alert')

    <form action="{{ route('transaction.store') }}" method="POST" id="transaction-form">
        @csrf

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Transaksi Baru</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Input transaksi penjualan produk ke pelanggan.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('transaction.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600">
                    Simpan Transaksi
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- LEFT: Table Produk (md-9) --}}
            <div class="w-full lg:w-9/12">
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Produk</h2>
                        <button type="button" id="transaction-add-item"
                            class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-700">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 w-96">Harga Jual</th>
                            <th class="px-4 py-3 w-20">Qty</th>
                            <th class="px-4 py-3 w-96">Subtotal</th>
                            <th class="px-4 py-3 text-center w-16">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-items-body">
                                @php($oldItems = old('items', [['product_id' => '', 'quantity' => '1']]))
                                @foreach ($oldItems as $index => $item)
                                    @include('transaksi.transaction.partials.item-row', ['index' => $index, 'item' => $item, 'products' => $products])
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 dark:bg-gray-800 font-semibold">
                                    <td class="px-4 py-3" colspan="3">Total</td>
                                    <td class="px-4 py-3" id="transaction-total-qty">0</td>
                                    <td class="px-4 py-3" id="transaction-total-subtotal">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Informasi Transaksi (md-3) --}}
            <div class="w-full lg:w-3/12">
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 space-y-5">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Transaksi</h2>

                    @if ($canSelectBranch)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabang</label>
                            <select id="transaction-branch-id" name="branch_id"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(old('branch_id', $selectedBranchId) == $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Transaksi</label>
                        <input type="text" id="transaction-code-preview" value="{{ $nextCode }}" readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi</label>
                        @include('partials.date-input', [
                            'name' => 'transaction_date',
                            'value' => old('transaction_date', now()->format('Y-m-d')),
                            'required' => true,
                        ])
                        @error('transaction_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metode Pembayaran</label>
                        <select name="payment_method" required
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->value }}" @selected(old('payment_method', 'cash') == $method->value)>
                                    {{ $method->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subtotal</label>
                        <input type="text" id="transaction-subtotal" value="Rp 0" readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white cursor-not-allowed font-semibold">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diskon</label>
                        @include('partials.formatted-number-input', [
                            'name' => 'discount',
                            'value' => old('discount', '0'),
                            'prefix' => 'Rp',
                            'placeholder' => '0',
                        ])
                        @error('discount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Akhir</label>
                        <input type="text" id="transaction-total" value="Rp 0" readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white cursor-not-allowed font-semibold text-blue-600 dark:text-blue-400">
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Bayar</label>
                        @include('partials.formatted-number-input', [
                            'name' => 'paid_amount',
                            'value' => old('paid_amount', '0'),
                            'prefix' => 'Rp',
                            'placeholder' => '0',
                        ])
                        @error('paid_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kembalian</label>
                        <input type="text" id="transaction-change" value="Rp 0" readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white cursor-not-allowed font-semibold text-green-600 dark:text-green-400">
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="mt-6">
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" rows="2" placeholder="Catatan transaksi (opsional)..."
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">{{ old('notes') }}</textarea>
            </div>
        </div>
    </form>
</div>

<template id="transaction-item-template">
    @include('transaksi.transaction.partials.item-row', ['index' => '__INDEX__', 'item' => ['product_id' => '', 'quantity' => ''], 'products' => $products])
</template>
@endsection

@include('partials.date-picker-assets')

@push('scripts')
    <script>
        window.transactionCatalog = @json($branchCatalog);
        window.transactionInitialBranchId = {{ (int) old('branch_id', $selectedBranchId) }};
    </script>
    <script src="/assets/js/formatted-number-input.js"></script>
    <script src="/assets/js/transaction-form.js"></script>
@endpush
