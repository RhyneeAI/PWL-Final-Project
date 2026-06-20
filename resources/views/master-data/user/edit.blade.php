@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Edit Pengguna
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                @if ($isEditingSelf)
                    Perbarui data akun Anda.
                @else
                    Perbarui role dan status pengguna. Data profil tidak dapat diubah.
                @endif
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

                    @if ($isEditingSelf)
                        <input type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Nama lengkap pengguna"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                    @else
                        <input type="text"
                            value="{{ $user->name }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Username
                    </label>

                    @if ($isEditingSelf)
                        <input type="text"
                            name="username"
                            value="{{ old('username', $user->username) }}"
                            placeholder="username"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                    @else
                        <input type="text"
                            value="{{ $user->username }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>

                    @if ($isEditingSelf)
                        <input type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="pengguna@email.com"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                    @else
                        <input type="email"
                            value="{{ $user->email }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    @endif
                </div>

                @if ($isEditingSelf)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password Baru
                        </label>

                        <input type="password"
                            name="password"
                            placeholder="Kosongkan jika tidak diubah"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role
                    </label>

                    @if ($isEditingSelf)
                        <select id="user-role-select" name="role"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                            @foreach ($assignableRoles as $role)
                                <option value="{{ $role->value }}" @selected(old('role', $user->role->value) === $role->value)>
                                    {{ $role->label() }}
                                </option>
                            @endforeach

                        </select>
                    @else
                        <select name="role"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                            @foreach ($assignableRoles as $role)
                                <option value="{{ $role->value }}" @selected(old('role', $user->role->value) === $role->value)>
                                    {{ $role->label() }}
                                </option>
                            @endforeach

                        </select>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cabang
                    </label>

                    @if ($isEditingSelf)
                        <div id="user-branch-field" @class(['hidden' => $user->role === \App\Enums\UserRole::Owner])>
                            <select name="branch_id"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(old('branch_id', $user->primaryBranchId()) == $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="user-head-office-field" @class(['hidden' => $user->role !== \App\Enums\UserRole::Owner])>
                            <input type="text"
                                value="Kantor Pusat"
                                readonly
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                    @else
                        <input type="text"
                            value="{{ $user->branchLabel() }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>

                    <select name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                        <option value="1" @selected(old('is_active', $user->is_active ? '1' : '0') == '1')>
                            Aktif
                        </option>

                        <option value="0" @selected(old('is_active', $user->is_active ? '1' : '0') == '0')>
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

@push('scripts')
    @if ($isEditingSelf)
        <script src="/assets/js/user-branch-form.js"></script>
    @endif
@endpush
