<?php

namespace App\Http\Controllers;

use App\Enums\ProductUnit;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ToggleActiveStatusRequest;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $products = Product::query()
            ->with(['category', 'branch'])
            ->when(! $user->isOwner(), fn ($query) => $query->whereIn('branch_id', $user->accessibleBranchIds()))
            ->latest()
            ->get();

        $branches = $this->filterBranches();

        return view('master-data.product.index', [
            'products' => $products,
            'branches' => $branches,
            'canSelectBranch' => $user->canSelectBranch(),
        ]);
    }

    public function create(): View
    {
        $branches = $this->assignableBranches();
        $selectedBranchId = (int) old('branch_id', $branches->first()?->id);
        $nextCode = $selectedBranchId
            ? Product::generateNextCode($selectedBranchId)
            : 'PRD-001';

        return view('master-data.product.create', [
            'branches' => $branches,
            'categories' => $this->activeCategories(),
            'units' => ProductUnit::cases(),
            'canSelectBranch' => auth()->user()->canSelectBranch(),
            'selectedBranchId' => $selectedBranchId,
            'nextCode' => $nextCode,
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $payload = $request->validated();

        if (! $user->canSelectBranch()) {
            $payload['branch_id'] = $user->primaryBranchId();
        }

        $branchId = (int) $payload['branch_id'];

        Product::create([
            ...$payload,
            'code' => Product::generateNextCode($branchId),
            'stock' => 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        abort_unless(auth()->user()->hasAccessToBranch($product->branch_id), 403);

        return view('master-data.product.edit', [
            'product' => $product,
            'branches' => $this->assignableBranches(),
            'categories' => $this->activeCategories(),
            'units' => ProductUnit::cases(),
            'canSelectBranch' => auth()->user()->canSelectBranch(),
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless(auth()->user()->hasAccessToBranch($product->branch_id), 403);

        $product->load(['category', 'branch']);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $mutations = $product->stockMutations()
            ->with(['supplier', 'transaction'])
            ->when($dateFrom, fn ($query) => $query->whereDate('mutation_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('mutation_date', '<=', $dateTo))
            ->orderByDesc('mutation_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('master-data.product.show', [
            'product' => $product,
            'mutations' => $mutations,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'canManage' => auth()->user()->role->canManageProducts(),
            'canViewStockIn' => auth()->user()->role->canManageStock(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        abort_unless(auth()->user()->hasAccessToBranch($product->branch_id), 403);

        $payload = $request->validated();

        if (! auth()->user()->canSelectBranch()) {
            unset($payload['branch_id']);
        }

        $product->update([
            ...$payload,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_unless(auth()->user()->hasAccessToBranch($product->branch_id), 403);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function updateActive(ToggleActiveStatusRequest $request, Product $product): JsonResponse
    {
        abort_unless(auth()->user()->hasAccessToBranch($product->branch_id), 403);

        $product->update(['is_active' => $request->boolean('is_active')]);

        return response()->json(['is_active' => $product->is_active]);
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

    private function filterBranches(): Collection
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            return Branch::query()->orderBy('name')->get();
        }

        return $user->branches()->orderBy('name')->get();
    }

    private function activeCategories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
