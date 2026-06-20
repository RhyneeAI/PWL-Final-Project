<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::query()->latest()->get();

        return view('master-data.branch.index', compact('branches'));
    }

    public function create(): View
    {
        return view('master-data.branch.create', [
            'nextCode' => Branch::generateNextCode(),
        ]);
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        Branch::create([
            ...$request->validated(),
            'code' => Branch::generateNextCode(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch): View
    {
        return view('master-data.branch.edit', compact('branch'));
    }

    public function update(BranchRequest $request, Branch $branch): RedirectResponse
    {
        $branch->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
