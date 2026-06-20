<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('master-data.user.index', compact('users'));
    }

    public function create()
    {
        return view('master-data.user.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return view('master-data.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
