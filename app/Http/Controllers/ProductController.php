<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'branch'])
            ->latest()
            ->get();

        return view('master-data.product.index', compact('products'));
    }

    public function create(): View
    {
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        return view('master-data.product.create', compact('branches', 'categories'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create([
            ...$request->validated(),
            'stock' => 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        return view('master-data.product.edit', compact('product', 'branches', 'categories'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
