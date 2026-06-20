<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! ($this->user()?->role->canManageUsers() ?? false)) {
            return false;
        }

        /** @var User|null $targetUser */
        $targetUser = $this->route('user');

        if ($targetUser && ! $targetUser->canBeManagedBy($this->user())) {
            return false;
        }

        return true;
    }

    protected function prepareForValidation(): void
    {
        $merged = [];

        if ($this->has('is_active')) {
            $merged['is_active'] = $this->boolean('is_active');
        }

        if ($this->input('role') === UserRole::Owner->value) {
            $merged['branch_id'] = null;
        }

        if ($merged !== []) {
            $this->merge($merged);
        }
    }

    public function rules(): array
    {
        /** @var User|null $targetUser */
        $targetUser = $this->route('user');
        $assignableRoles = UserRole::assignableValuesBy($this->user()->role);

        if ($targetUser && $this->isEditingOtherUser($targetUser)) {
            return [
                'name' => ['prohibited'],
                'username' => ['prohibited'],
                'email' => ['prohibited'],
                'password' => ['prohibited'],
                'branch_id' => ['prohibited'],
                'role' => ['required', Rule::in($assignableRoles)],
                'is_active' => ['required', 'boolean'],
            ];
        }

        $userId = $targetUser?->id;
        $requiresBranch = $this->input('role') !== UserRole::Owner->value;

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::in($assignableRoles)],
            'branch_id' => [
                Rule::requiredIf($requiresBranch),
                'nullable',
                'exists:branches,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null) {
                        return;
                    }

                    if (! $this->user()->hasAccessToBranch((int) $value)) {
                        $fail('Cabang tidak valid atau tidak dapat diakses.');
                    }
                },
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak diizinkan.',
            'branch_id.required' => 'Cabang wajib dipilih.',
            'branch_id.exists' => 'Cabang tidak ditemukan.',
            'is_active.required' => 'Status wajib dipilih.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatedPayload(?User $targetUser = null): array
    {
        $targetUser ??= $this->route('user');
        $validated = $this->validated();
        unset($validated['branch_id']);

        if ($targetUser && $this->isEditingOtherUser($targetUser)) {
            $payload = [
                'is_active' => $validated['is_active'],
            ];

            if (array_key_exists('role', $validated)) {
                $payload['role'] = $validated['role'];
            }

            return $payload;
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        return $validated;
    }

    public function syncBranches(User $user): void
    {
        /** @var User|null $targetUser */
        $targetUser = $this->route('user');

        if ($targetUser && $this->isEditingOtherUser($targetUser)) {
            return;
        }

        $role = UserRole::from($this->input('role', $user->role->value));

        if ($role === UserRole::Owner) {
            $user->branches()->sync([]);

            return;
        }

        $branchId = $this->input('branch_id');

        if ($branchId) {
            $user->branches()->sync([(int) $branchId]);
        }
    }

    private function isEditingOtherUser(User $targetUser): bool
    {
        return $targetUser->exists && $targetUser->id !== $this->user()->id;
    }
}
