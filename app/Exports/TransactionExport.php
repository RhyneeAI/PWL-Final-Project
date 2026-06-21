<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionExport
{
    public function __construct(
        private Collection $transactions,
        private bool $groupByBranch = false,
        private float $grandTotal = 0,
        private ?string $startDate = null,
        private ?string $endDate = null,
    ) {}

    public function download(string $filename)
    {
        $groupByBranch = $this->groupByBranch;
        $grouped = $groupByBranch
            ? $this->transactions->groupBy(fn (Transaction $trx) => $trx->branch->name)
            : null;

        $html = view('reports.excel', [
            'transactions' => $this->transactions,
            'grandTotal' => $this->grandTotal,
            'groupByBranch' => $this->groupByBranch,
            'grouped' => $grouped,
        ])->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
