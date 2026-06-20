<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with('branches')
            ->get()
            ->sortBy([
                fn (User $user) => $user->role->listOrder(),
                fn (User $user) => $user->name,
            ])
            ->values();

        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();
        $roleFilterOptions = UserRole::displayOrder();

        return view('master-data.user.index', compact('users', 'branches', 'roleFilterOptions'));
    }

    public function create(): View
    {
        $assignableRoles = UserRole::assignableBy(auth()->user()->role);
        $branches = $this->availableBranches();

        return view('master-data.user.create', compact('assignableRoles', 'branches'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::create($request->validatedPayload());
        $request->syncBranches($user);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        abort_unless($user->canBeManagedBy(auth()->user()), 403);

        $user->load('branches');
        $assignableRoles = UserRole::assignableBy(auth()->user()->role);
        $isEditingSelf = auth()->id() === $user->id;
        $branches = $this->availableBranches();

        return view('master-data.user.edit', compact(
            'user',
            'assignableRoles',
            'isEditingSelf',
            'branches',
        ));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validatedPayload($user));
        $request->syncBranches($user);

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

    private function availableBranches(): Collection
    {
        $actor = auth()->user();

        if ($actor->isOwner()) {
            return Branch::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return $actor->branches()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
