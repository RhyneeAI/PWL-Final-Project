<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $accessibleBranchIds = $user->accessibleBranchIds();

        $totalOrders = Transaction::query()
            ->whereIn('branch_id', $accessibleBranchIds)
            ->count();

        $totalProducts = Product::query()
            ->whereIn('branch_id', $accessibleBranchIds)
            ->where('is_active', true)
            ->count();

        $totalRevenue = Transaction::query()
            ->whereIn('branch_id', $accessibleBranchIds)
            ->where('status', '!=', TransactionStatus::Cancelled)
            ->sum(DB::raw('total'));

        $recentTransactions = Transaction::query()
            ->with(['branch', 'items'])
            ->whereIn('branch_id', $accessibleBranchIds)
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalOrders', 'totalProducts', 'totalRevenue', 'recentTransactions',
        ));
    }
}
