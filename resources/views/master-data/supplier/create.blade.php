@extends('layouts.main')

@section('title', 'Tambah Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Tambah Supplier
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tambahkan supplier baru.
            </p>
        </div>

        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cabang
                    </label>

                    <select name="branch_id"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kode Supplier
                    </label>

                    <input type="text"
                        name="code"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Supplier
                    </label>

                    <input type="text"
                        name="name"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Telepon
                    </label>

                    <input type="text"
                        name="phone"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Alamat
                </label>

                <textarea
                    name="address"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white"></textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('supplier.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600">
                    Simpan Supplier
                </button>

            </div>
        </form>

    </div>
</div>
@endsection