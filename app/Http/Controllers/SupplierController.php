<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Requests\ToggleActiveStatusRequest;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $suppliers = Supplier::query()
            ->with('branch')
            ->when(! $user->isOwner(), fn ($query) => $query->whereIn('branch_id', $user->accessibleBranchIds()))
            ->latest()
            ->get();

        $branches = $this->filterBranches();

        return view('master-data.supplier.index', [
            'suppliers' => $suppliers,
            'branches' => $branches,
            'canSelectBranch' => $user->canSelectBranch(),
        ]);
    }

    public function create(): View
    {
        $branches = $this->assignableBranches();
        $canSelectBranch = auth()->user()->canSelectBranch();
        $selectedBranchId = (int) old('branch_id', $branches->first()?->id);
        $nextCode = $selectedBranchId
            ? Supplier::generateNextCode($selectedBranchId)
            : 'SUP-001';

        return view('master-data.supplier.create', compact(
            'branches',
            'selectedBranchId',
            'nextCode',
            'canSelectBranch',
        ));
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $branchId = auth()->user()->canSelectBranch()
            ? (int) $request->branch_id
            : (int) auth()->user()->primaryBranchId();

        Supplier::create([
            ...$request->validated(),
            'branch_id' => $branchId,
            'code' => Supplier::generateNextCode($branchId),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier): View
    {
        abort_unless(auth()->user()->hasAccessToBranch($supplier->branch_id), 403);

        $supplier->load('branch');

        return view('master-data.supplier.edit', [
            'supplier' => $supplier,
            'canSelectBranch' => auth()->user()->canSelectBranch(),
        ]);
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        abort_unless(auth()->user()->hasAccessToBranch($supplier->branch_id), 403);

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
        abort_unless(auth()->user()->hasAccessToBranch($supplier->branch_id), 403);

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    public function updateActive(ToggleActiveStatusRequest $request, Supplier $supplier): JsonResponse
    {
        abort_unless(auth()->user()->hasAccessToBranch($supplier->branch_id), 403);

        $supplier->update(['is_active' => $request->boolean('is_active')]);

        return response()->json(['is_active' => $supplier->is_active]);
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
}
