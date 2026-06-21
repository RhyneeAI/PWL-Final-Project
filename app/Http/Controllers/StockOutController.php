<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockOutRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\User;
use App\Enums\StockMutationType;
use App\Services\StockOutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockOutController extends Controller
{
    public function __construct(private StockOutService $stockOutService) {}

    public function index(): View
    {
        $user = auth()->user();
        $canSelectBranch = $user->canSelectBranch();

        $stockOuts = StockMutation::query()
            ->where('type', StockMutationType::AdjustOut)
            ->when(! $user->isOwner(), fn ($query) => $query->whereIn('branch_id', $user->accessibleBranchIds()))
            ->select([
                'reference_code',
                'branch_id',
                'user_id',
                DB::raw('MIN(mutation_date) as mutation_date'),
                DB::raw('SUM(ABS(quantity_change)) as total_quantity'),
                DB::raw('COUNT(*) as total_items'),
            ])
            ->groupBy('reference_code', 'branch_id', 'user_id')
            ->orderByDesc(DB::raw('MIN(mutation_date)'))
            ->get();

        $branches = Branch::query()
            ->whereIn('id', $stockOuts->pluck('branch_id')->unique())
            ->get()
            ->keyBy('id');

        $users = User::query()
            ->whereIn('id', $stockOuts->pluck('user_id')->unique())
            ->get()
            ->keyBy('id');

        $filterBranches = $canSelectBranch
            ? Branch::query()->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('transaksi.stock-out.index', [
            'stockOuts' => $stockOuts,
            'branches' => $filterBranches,
            'branchMap' => $branches,
            'userMap' => $users,
            'canSelectBranch' => $canSelectBranch,
        ]);
    }

    public function create(): View
    {
        $user = auth()->user();
        $branches = $this->assignableBranches();
        $canSelectBranch = $user->canSelectBranch();
        $selectedBranchId = (int) old('branch_id', $branches->first()?->id);
        $catalog = $this->branchCatalog($selectedBranchId);

        return view('transaksi.stock-out.create', [
            'branches' => $branches,
            'canSelectBranch' => $canSelectBranch,
            'selectedBranchId' => $selectedBranchId,
            'nextCode' => $selectedBranchId
                ? StockMutation::generateNextReferenceCodeForOut($selectedBranchId)
                : 'STO-001',
            'products' => $catalog['products'],
            'branchCatalog' => $this->buildBranchCatalog($branches),
        ]);
    }

    public function store(StockOutRequest $request): RedirectResponse
    {
        $referenceCode = $this->stockOutService->store(
            branchId: $request->resolvedBranchId(),
            items: $request->normalizedItems(),
            actor: $request->user(),
            mutationDate: $request->input('mutation_date'),
            notes: $request->input('notes'),
        );

        return redirect()
            ->route('stock-out.show', $referenceCode)
            ->with('success', 'Stok keluar berhasil dicatat.');
    }

    public function show(string $referenceCode): View
    {
        $user = auth()->user();

        $items = StockMutation::query()
            ->where('type', StockMutationType::AdjustOut)
            ->where('reference_code', $referenceCode)
            ->with(['product', 'branch', 'user'])
            ->orderBy('id')
            ->get();

        abort_if($items->isEmpty(), 404);

        abort_unless($user->hasAccessToBranch($items->first()->branch_id), 403);

        $totalQuantity = $items->sum(fn (StockMutation $item) => abs($item->quantity_change));

        return view('transaksi.stock-out.show', [
            'items' => $items,
            'referenceCode' => $referenceCode,
            'header' => $items->first(),
            'totalQuantity' => $totalQuantity,
        ]);
    }

    private function assignableBranches(): Collection
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            return Branch::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return $user->branches()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array{products: Collection}
     */
    private function branchCatalog(int $branchId): array
    {
        return [
            'products' => Product::query()
                ->where('branch_id', $branchId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * @return array<int, array{products: list<array{id: int, name: string, code: string, unit: string}>}>
     */
    private function buildBranchCatalog(Collection $branches): array
    {
        $catalog = [];

        foreach ($branches as $branch) {
            $data = $this->branchCatalog($branch->id);

            $catalog[$branch->id] = [
                'products' => $data['products']->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'unit' => $product->unit->label(),
                    'stock' => $product->stock,
                ])->values()->all(),
                'next_code' => StockMutation::generateNextReferenceCodeForOut($branch->id),
            ];
        }

        return $catalog;
    }
}
