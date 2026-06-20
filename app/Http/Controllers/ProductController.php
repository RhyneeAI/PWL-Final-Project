<?php

namespace App\Http\Controllers;

use App\Enums\ProductUnit;
use App\Http\Requests\ProductRequest;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
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

        return view('master-data.product.index', compact('products', 'branches'));
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
        Product::create([
            ...$request->validated(),
            'code' => Product::generateNextCode((int) $request->branch_id),
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
