<table>
    <thead>
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
    </thead>
    <tbody>
        @forelse ($transactions as $trx)
            <tr class="trx-header">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $trx->code }}</td>
                <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $trx->user?->name ?? '-' }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($trx->items as $item)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
            <tr>
                <td colspan="8" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
