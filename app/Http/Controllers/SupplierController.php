<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::query()->with('branch')->latest()->get();
        $branches = Branch::query()->orderBy('name')->get();

        return view('master-data.supplier.index', compact('suppliers', 'branches'));
    }

    public function create(): View
    {
        $branches = Branch::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedBranchId = (int) old('branch_id', $branches->first()?->id);
        $nextCode = $selectedBranchId
            ? Supplier::generateNextCode($selectedBranchId)
            : 'SUP-001';

        return view('master-data.supplier.create', compact(
            'branches',
            'selectedBranchId',
            'nextCode',
        ));
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        Supplier::create([
            ...$request->validated(),
            'code' => Supplier::generateNextCode((int) $request->branch_id),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier): View
    {
        $supplier->load('branch');

        return view('master-data.supplier.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
