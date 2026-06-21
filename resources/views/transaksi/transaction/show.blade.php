@extends('layouts.main')
@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @include('partials.session-alert')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Detail Transaksi</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $transaction->code }}</p>
        </div>
        <a href="{{ route('transaction.index') }}"
            class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Transaksi</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">No Transaksi</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $transaction->code }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Tanggal</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Cabang</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $transaction->branch->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Kasir</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $transaction->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Status</p>
                    <p><span class="badge-{{ $transaction->status->badgeColor() }}">{{ $transaction->status->label() }}</span></p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Pembayaran</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $transaction->payment_method->label() }}</p>
                </div>
            </div>

            @if ($transaction->notes)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Catatan</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $transaction->notes }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Rincian Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                    <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($transaction->discount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Diskon</span>
                        <span class="font-semibold text-red-600 dark:text-red-400">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <hr class="border-gray-200 dark:border-gray-700">
                <div class="flex justify-between text-lg">
                    <span class="font-semibold text-gray-800 dark:text-white">Total</span>
                    <span class="font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Total Bayar</span>
                    <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Kembalian</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Daftar Produk</h2>
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Harga Jual</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($transaction->items as $index => $item)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->product?->code ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->product_name }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
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
