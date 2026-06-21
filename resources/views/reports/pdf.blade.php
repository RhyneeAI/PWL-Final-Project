<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 12px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; }
        th { background: #f5f5f5; font-weight: 600; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: 700; }
        .grand-total { font-size: 14px; text-align: right; margin-top: 12px; }
        .trx-header td { font-weight: 600; background: #fafafa; }
        .trx-total td { border-top: 1px solid #999; font-weight: 600; background: #fafafa; }
        .branch-title { font-size: 14px; font-weight: 700; margin: 20px 0 8px; padding: 6px 10px; background: #eee; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <p class="subtitle">{{ \Illuminate\Support\Carbon::parse($startDate)->format('d/m/Y') }} – {{ \Illuminate\Support\Carbon::parse($endDate)->format('d/m/Y') }}</p>

    @if ($groupByBranch && $grouped)
        @foreach ($grouped as $branchName => $branchTransactions)
            <div class="branch-title">{{ $branchName }}</div>
            @include('reports._pdf-table', ['transactions' => $branchTransactions])

            @if (! $loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach

        <div class="grand-total">
            Grand Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
        </div>
    @else
        @include('reports._pdf-table', ['transactions' => $transactions])
        <div class="grand-total">
            Grand Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
        </div>
    @endif
</body>
</html>
