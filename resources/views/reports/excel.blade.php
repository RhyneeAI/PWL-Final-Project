<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { border-collapse: collapse; width: 100%; font-family: sans-serif; font-size: 11px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; }
        th { background: #f5f5f5; font-weight: 700; }
        .text-right { text-align: right; }
        .branch-title { font-size: 14px; font-weight: 700; padding: 8px 10px; background: #e8e8e8; }
        .trx-header td { font-weight: 600; background: #fafafa; }
        .trx-total td { border-top: 2px solid #999; font-weight: 600; background: #fafafa; }
        .grand-total td { font-size: 13px; font-weight: 700; border-top: 2px solid #333; }
    </style>
</head>
<body>
    @if ($groupByBranch && $grouped)
        @foreach ($grouped as $branchName => $branchTransactions)
            <table>
                <tr><td class="branch-title" colspan="8">{{ $branchName }}</td></tr>
                <tr>
                    <th>No</th>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Produk</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
                @foreach ($branchTransactions as $trx)
                    <tr class="trx-header">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $trx->code }}</td>
                        <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                        <td>{{ $trx->user->name }}</td>
                        <td></td><td></td><td></td><td></td>
                    </tr>
                    @foreach ($trx->items as $item)
                        <tr>
                            <td></td><td></td><td></td><td></td>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-right">{{ number_format($item->product_price, 0, ',', '.') }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="trx-total">
                        <td colspan="7" class="text-right">Total Transaksi</td>
                        <td class="text-right">{{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
            <br>
        @endforeach
        <table>
            <tr class="grand-total">
                <td colspan="7" class="text-right">Grand Total</td>
                <td class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    @else
        <table>
            <tr>
                <th>No</th>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Produk</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
            @forelse ($transactions as $trx)
                <tr class="trx-header">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trx->code }}</td>
                    <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $trx->user->name }}</td>
                    <td></td><td></td><td></td><td></td>
                </tr>
                @foreach ($trx->items as $item)
                    <tr>
                        <td></td><td></td><td></td><td></td>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">{{ number_format($item->product_price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="trx-total">
                    <td colspan="7" class="text-right">Total Transaksi</td>
                    <td class="text-right">{{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
            <tr class="grand-total">
                <td colspan="7" class="text-right">Grand Total</td>
                <td class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    @endif
</body>
</html>
