@extends('layouts.main')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Tambah Pengguna
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tambahkan pengguna baru ke sistem.
            </p>
        </div>

        <form action="{{ route('user.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama
                    </label>

                    <input type="text"
                        name="name"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Username
                    </label>

                    <input type="text"
                        name="username"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>

                    <input type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role
                    </label>

                    <select name="role"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        <option value="owner">Owner</option>
                        <option value="manager">Manager</option>
                        <option value="cashier">Kasir</option>
                        <option value="warehouse">Pegawai Gudang</option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>

                    <select name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>

                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('user.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600">
                    Simpan Pengguna
                </button>

            </div>
        </form>

    </div>
</div>
@endsection