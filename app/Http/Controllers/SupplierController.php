<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();

        return view('master-data.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        $branches = Branch::all();

        return view('master-data.supplier.create', compact('branches'));
    }

    public function store(Request $request)
    {
        Supplier::create([
            'branch_id' => $request->branch_id,
            'code' => $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'is_active' => true,
        ]);

        return redirect()->route('supplier.index');
    }

    public function edit(Supplier $supplier)
    {
        $branches = Branch::all();

        return view('master-data.supplier.edit', compact('supplier', 'branches'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update([
            'code' => $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('supplier.index');
    }

       

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('supplier.index');
    }
}