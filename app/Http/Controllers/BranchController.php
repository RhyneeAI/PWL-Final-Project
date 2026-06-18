<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();

        return view('master-data.branch.index', compact('branches'));
    }

    //create
    public function create()
    {
        return view('master-data.branch.create');
    }

    public function store(Request $request)
    {
        Branch::create([
            'code' => $request->code,
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('branch.index');
    }

    //update
    public function edit(Branch $branch)
    {
        return view('master-data.branch.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $branch->update([
            'code' => $request->code,
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('branch.index');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branch.index');
    }
}