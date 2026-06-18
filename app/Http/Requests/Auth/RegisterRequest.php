<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'username'              => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(6)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Nama wajib diisi.',
            'name.max'                       => 'Nama maksimal 255 karakter.',
            'username.required'              => 'Username wajib diisi.',
            'username.min'                   => 'Username minimal 3 karakter.',
            'username.max'                   => 'Username maksimal 50 karakter.',
            'username.alpha_dash'            => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'username.unique'                => 'Username sudah digunakan.',
            'email.required'                 => 'Email wajib diisi.',
            'email.email'                    => 'Format email tidak valid.',
            'email.unique'                   => 'Email sudah terdaftar.',
            'password.required'              => 'Password wajib diisi.',
            'password.confirmed'             => 'Konfirmasi password tidak cocok.',
            'password.min'                   => 'Password minimal 6 karakter.',
        ];
    }
}
