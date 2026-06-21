<?php

namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Models\Branch;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $canSelectBranch = $user->canSelectBranch();

        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        $transactions = $this->filteredTransactions($startDate, $endDate, $branchId);

        $branches = $canSelectBranch
            ? Branch::query()->where('is_active', true)->orderBy('name')->get()
            : collect();

        $grandTotal = $transactions->sum('total');

        return view('reports.index', compact(
            'transactions', 'branches', 'canSelectBranch',
            'startDate', 'endDate', 'branchId', 'grandTotal',
        ));
    }

    public function pdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        $transactions = $this->filteredTransactions($startDate, $endDate, $branchId);

        $grandTotal = $transactions->sum('total');
        $groupByBranch = auth()->user()->isOwner() && ! $branchId;

        if ($groupByBranch) {
            $grouped = $transactions->groupBy(fn (Transaction $trx) => $trx->branch->name);
        } else {
            $grouped = null;
        }

        $pdf = Pdf::loadView('reports.pdf', compact(
            'transactions', 'startDate', 'endDate', 'grandTotal', 'groupByBranch', 'grouped',
        ));

        $filename = 'laporan-penjualan-' . $startDate . '-sampai-' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    public function excel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        $transactions = $this->filteredTransactions($startDate, $endDate, $branchId);

        $filename = 'laporan-penjualan-' . $startDate . '-sampai-' . $endDate . '.xlsx';

        $groupByBranch = auth()->user()->isOwner() && ! $branchId;

        $export = new TransactionExport($transactions, $groupByBranch);
        $export->download($filename);
        exit;
    }

    private function filteredTransactions(string $startDate, string $endDate, ?string $branchId)
    {
        $user = auth()->user();
        $canSelectBranch = $user->canSelectBranch();

        return Transaction::query()
            ->with(['items', 'branch', 'user'])
            ->whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when(! $user->isOwner(), fn ($q) => $q->whereIn('branch_id', $user->accessibleBranchIds()))
            ->when($canSelectBranch && $branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->orderBy('transaction_date')
            ->get();
    }
}
