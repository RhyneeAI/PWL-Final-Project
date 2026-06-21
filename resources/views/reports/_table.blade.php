@php
$showBranch = $canSelectBranch ?? false;
@endphp

<div class="overflow-x-auto">
    <table class="w-full min-w-full">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">No Transaksi</th>
                <th class="px-4 py-3">Tanggal</th>
                @if ($showBranch)
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
                    @if ($showBranch)
                        <td class="px-4 py-3">{{ $trx->branch?->name ?? '-' }}</td>
                    @endif
                    <td class="px-4 py-3">{{ $trx->user?->name ?? '-' }}</td>
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
                        @if ($showBranch)
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
                    <td colspan="{{ $showBranch ? 10 : 9 }}" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($grandTotal !== null)
            <tfoot>
                <tr class="bg-gray-50 dark:bg-gray-800 font-semibold">
                    <td colspan="{{ $showBranch ? 9 : 8 }}" class="px-4 py-3 text-right">Grand Total</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>