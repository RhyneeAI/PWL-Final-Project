@extends('layouts.main')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Tambah Pengguna
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tambahkan pengguna baru ke sistem.
            </p>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama
                    </label>

                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap pengguna"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Username
                    </label>

                    <input type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="username"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="pengguna@email.com"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>

                    <input type="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role
                    </label>

                    <select id="user-role-select" name="role"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        @foreach ($assignableRoles as $role)
                            <option value="{{ $role->value }}" @selected(old('role', $assignableRoles[0]->value ?? '') === $role->value)>
                                {{ $role->label() }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div id="user-branch-field">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cabang
                    </label>

                    <select name="branch_id"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="user-head-office-field" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cabang
                    </label>

                    <input type="text"
                        value="Kantor Pusat"
                        readonly
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>

                    <select name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        <option value="1" @selected(old('is_active', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active') === '0')>Nonaktif</option>

                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('users.index') }}"
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

@push('scripts')
    <script src="/assets/js/user-branch-form.js"></script>
@endpush
