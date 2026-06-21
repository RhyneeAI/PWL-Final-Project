<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Http\Requests\TransactionRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService) {}

    public function index(): View
    {
        $user = auth()->user();
        $canSelectBranch = $user->canSelectBranch();

        $transactions = Transaction::query()
            ->with(['branch', 'user'])
            ->when(! $user->isOwner(), fn ($query) => $query->whereIn('branch_id', $user->accessibleBranchIds()))
            ->orderByDesc('transaction_date')
            ->get();

        $branches = $canSelectBranch
            ? Branch::query()->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('transaksi.transaction.index', [
            'transactions' => $transactions,
            'branches' => $branches,
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

        return view('transaksi.transaction.create', [
            'branches' => $branches,
            'canSelectBranch' => $canSelectBranch,
            'selectedBranchId' => $selectedBranchId,
            'nextCode' => $selectedBranchId
                ? Transaction::generateNextTransactionCode($selectedBranchId)
                : 'TRX-001',
            'paymentMethods' => PaymentMethod::cases(),
            'products' => $catalog['products'],
            'branchCatalog' => $this->buildBranchCatalog($branches),
        ]);
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        $transaction = $this->transactionService->store(
            branchId: $request->resolvedBranchId(),
            items: $request->normalizedItems(),
            actor: $request->user(),
            transactionDate: $request->input('transaction_date'),
            paymentMethod: $request->input('payment_method'),
            paidAmount: (float) $request->input('paid_amount'),
            discount: (float) ($request->input('discount') ?? 0),
            notes: $request->input('notes'),
        );

        return redirect()
            ->route('transaction.show', $transaction)
            ->with('success', 'Transaksi penjualan berhasil dicatat.');
    }

    public function show(Transaction $transaction): View
    {
        $user = auth()->user();

        abort_unless($user->hasAccessToBranch($transaction->branch_id), 403);

        $transaction->load(['items.product', 'branch', 'user']);

        return view('transaksi.transaction.show', [
            'transaction' => $transaction,
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
     * @return array<int, array{products: list<array{id: int, name: string, code: string, sell_price: float, unit: string, stock: int}>}>
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
                    'sell_price' => (float) $product->sell_price,
                    'unit' => $product->unit->label(),
                    'stock' => $product->stock,
                ])->values()->all(),
                'next_code' => Transaction::generateNextTransactionCode($branch->id),
            ];
        }

        return $catalog;
    }
}
