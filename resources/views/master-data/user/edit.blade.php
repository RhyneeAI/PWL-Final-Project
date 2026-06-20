@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Edit Pengguna
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Perbarui data pengguna.
            </p>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama
                    </label>

                    <input type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Username
                    </label>

                    <input type="text"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role
                    </label>

                    <select name="role"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        <option value="owner" {{ $user->role->value == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="manager" {{ $user->role->value == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="cashier" {{ $user->role->value == 'cashier' ? 'selected' : '' }}>Kasir</option>
                        <option value="warehouse" {{ $user->role->value == 'warehouse' ? 'selected' : '' }}>Pegawai Gudang</option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>

                    <select name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('users.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600">
                    Update Pengguna
                </button>

            </div>
        </form>

    </div>
</div>
@endsection