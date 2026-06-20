<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->latest()->get();

        return view('master-data.user.index', compact('users'));
    }

    public function create(): View
    {
        $assignableRoles = UserRole::assignableBy(auth()->user()->role);

        return view('master-data.user.create', compact('assignableRoles'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        abort_unless($user->canBeManagedBy(auth()->user()), 403);

        $assignableRoles = UserRole::assignableBy(auth()->user()->role);
        $isEditingSelf = auth()->id() === $user->id;

        return view('master-data.user.edit', compact(
            'user',
            'assignableRoles',
            'isEditingSelf',
        ));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validatedPayload($user));

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        abort_unless($user->canBeManagedBy(auth()->user()), 403);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
