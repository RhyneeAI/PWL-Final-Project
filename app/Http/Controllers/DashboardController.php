<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
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

    public function chartData(Request $request)
    {
        $user = auth()->user();
        $accessibleBranchIds = $user->accessibleBranchIds();

        $period = $request->input('period', 'month');
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        if ($period === 'year') {
            $data = $this->yearlyData($year, $accessibleBranchIds);
        } else {
            $data = $this->monthlyData($year, $month, $accessibleBranchIds);
        }

        return response()->json($data);
    }

    private function monthlyData(int $year, int $month, array $branchIds): array
    {
        $daysInMonth = now()->create($year, $month, 1)->daysInMonth;
        $labels = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $labels[] = (string) $day;
        }

        $rows = Transaction::query()
            ->selectRaw('EXTRACT(DAY FROM transaction_date) as day, COUNT(*) as order_count, COALESCE(SUM(total), 0) as revenue')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->whereIn('branch_id', $branchIds)
            ->where('status', '!=', TransactionStatus::Cancelled)
            ->groupBy(DB::raw('EXTRACT(DAY FROM transaction_date)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $revenue = [];
        $orders = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $row = $rows->get($day);
            $revenue[] = (int) ($row?->revenue ?? 0);
            $orders[] = (int) ($row?->order_count ?? 0);
        }

        $monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
            'subtitle' => 'Per hari · ' . $monthNames[$month - 1] . ' ' . $year,
        ];
    }

    private function yearlyData(int $year, array $branchIds): array
    {
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $rows = Transaction::query()
            ->selectRaw('EXTRACT(MONTH FROM transaction_date) as month, COUNT(*) as order_count, COALESCE(SUM(total), 0) as revenue')
            ->whereYear('transaction_date', $year)
            ->whereIn('branch_id', $branchIds)
            ->where('status', '!=', TransactionStatus::Cancelled)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $revenue = [];
        $orders = [];

        for ($month = 1; $month <= 12; $month++) {
            $row = $rows->get($month);
            $revenue[] = (int) ($row?->revenue ?? 0);
            $orders[] = (int) ($row?->order_count ?? 0);
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
            'subtitle' => 'Per bulan · ' . $year,
        ];
    }
}
