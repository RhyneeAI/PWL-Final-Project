<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Supplier;
use App\Models\User;
use App\Enums\StockMutationType;
use App\Services\StockInService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockInController extends Controller
{
    public function __construct(private StockInService $stockInService) {}

    public function index(): View
    {
        $user = auth()->user();
        $canSelectBranch = $user->canSelectBranch();

        $stockIns = StockMutation::query()
            ->where('type', StockMutationType::AdjustIn)
            ->when(! $user->isOwner(), fn ($query) => $query->whereIn('branch_id', $user->accessibleBranchIds()))
            ->select([
                'reference_code',
                'branch_id',
                'supplier_id',
                'user_id',
                DB::raw('MIN(mutation_date) as mutation_date'),
                DB::raw('SUM(quantity_change) as total_quantity'),
                DB::raw('COUNT(*) as total_items'),
                DB::raw('SUM(COALESCE(buy_price, 0) * quantity_change) as total_amount'),
            ])
            ->groupBy('reference_code', 'branch_id', 'supplier_id', 'user_id')
            ->orderByDesc(DB::raw('MIN(mutation_date)'))
            ->get();

        $branches = Branch::query()
            ->whereIn('id', $stockIns->pluck('branch_id')->unique())
            ->get()
            ->keyBy('id');

        $suppliers = Supplier::query()
            ->whereIn('id', $stockIns->pluck('supplier_id')->unique())
            ->get()
            ->keyBy('id');

        $users = User::query()
            ->whereIn('id', $stockIns->pluck('user_id')->unique())
            ->get()
            ->keyBy('id');

        $filterBranches = $canSelectBranch
            ? Branch::query()->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('transaksi.stock-in.index', [
            'stockIns' => $stockIns,
            'branches' => $filterBranches,
            'branchMap' => $branches,
            'supplierMap' => $suppliers,
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

        return view('transaksi.stock-in.create', [
            'branches' => $branches,
            'canSelectBranch' => $canSelectBranch,
            'selectedBranchId' => $selectedBranchId,
            'nextCode' => $selectedBranchId
                ? StockMutation::generateNextReferenceCode($selectedBranchId)
                : 'STM-001',
            'suppliers' => $catalog['suppliers'],
            'products' => $catalog['products'],
            'branchCatalog' => $this->buildBranchCatalog($branches),
        ]);
    }

    public function store(StockInRequest $request): RedirectResponse
    {
        $referenceCode = $this->stockInService->store(
            branchId: $request->resolvedBranchId(),
            supplierId: (int) $request->input('supplier_id'),
            items: $request->normalizedItems(),
            actor: $request->user(),
            mutationDate: $request->input('mutation_date'),
            notes: $request->input('notes'),
        );

        return redirect()
            ->route('stock-mutation.show', $referenceCode)
            ->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function show(string $referenceCode): View
    {
        $user = auth()->user();

        $items = StockMutation::query()
            ->where('type', StockMutationType::AdjustIn)
            ->where('reference_code', $referenceCode)
            ->with(['product', 'supplier', 'branch', 'user'])
            ->orderBy('id')
            ->get();

        abort_if($items->isEmpty(), 404);

        abort_unless($user->hasAccessToBranch($items->first()->branch_id), 403);

        $totalQuantity = $items->sum('quantity_change');
        $totalAmount = $items->sum(fn (StockMutation $item) => $item->subtotal());

        return view('transaksi.stock-in.show', [
            'items' => $items,
            'referenceCode' => $referenceCode,
            'header' => $items->first(),
            'totalQuantity' => $totalQuantity,
            'totalAmount' => $totalAmount,
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
     * @return array{suppliers: Collection, products: Collection}
     */
    private function branchCatalog(int $branchId): array
    {
        return [
            'suppliers' => Supplier::query()
                ->where('branch_id', $branchId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'products' => Product::query()
                ->where('branch_id', $branchId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * @return array<int, array{suppliers: list<array{id: int, name: string}>, products: list<array{id: int, name: string, code: string, buy_price: float, unit: string}>}>
     */
    private function buildBranchCatalog(Collection $branches): array
    {
        $catalog = [];

        foreach ($branches as $branch) {
            $data = $this->branchCatalog($branch->id);

            $catalog[$branch->id] = [
                'suppliers' => $data['suppliers']->map(fn (Supplier $supplier) => [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                ])->values()->all(),
                'products' => $data['products']->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'buy_price' => (float) $product->buy_price,
                    'unit' => $product->unit->label(),
                ])->values()->all(),
                'next_code' => StockMutation::generateNextReferenceCode($branch->id),
            ];
        }

        return $catalog;
    }
}
