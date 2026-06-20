@extends('layouts.main')

@section('title', 'Edit Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Edit Supplier
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Perbarui data supplier.
            </p>
        </div>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kode Supplier
                    </label>
                    <input
                        type="text"
                        name="code"
                        value="{{ old('code', $supplier->code) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Supplier
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $supplier->name) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Telepon
                    </label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $supplier->phone) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $supplier->email) }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>

                    <select
                        name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3">

                        <option value="1" {{ $supplier->is_active ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0" {{ !$supplier->is_active ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Alamat
                </label>

                <textarea
                    name="address"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white
                           px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('address', $supplier->address) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('suppliers.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-300
                          text-gray-700 dark:text-gray-300
                          hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600">
                    Update Supplier
                </button>

            </div>
        </form>

    </div>
</div>
@endsection