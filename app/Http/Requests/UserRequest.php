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
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }
    }

    public function rules(): array
    {
        /** @var User|null $targetUser */
        $targetUser = $this->route('user');
        $actor = $this->user()->role;
        $assignableRoles = UserRole::assignableValuesBy($actor);

        if ($targetUser && $this->isEditingOtherUser($targetUser)) {
            return [
                'name' => ['prohibited'],
                'username' => ['prohibited'],
                'email' => ['prohibited'],
                'password' => ['prohibited'],
                'role' => ['required', Rule::in($assignableRoles)],
                'is_active' => ['required', 'boolean'],
            ];
        }

        $userId = $targetUser?->id;

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
            'role.prohibited' => 'Anda tidak dapat mengubah role pengguna ini.',
            'is_active.required' => 'Status wajib dipilih.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatedPayload(User $targetUser): array
    {
        $validated = $this->validated();

        if ($this->isEditingOtherUser($targetUser)) {
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

    private function isEditingOtherUser(User $targetUser): bool
    {
        return $targetUser->id !== $this->user()->id;
    }
}
