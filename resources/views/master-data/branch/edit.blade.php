@extends('layouts.main')

@section('title', 'Edit Cabang')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Edit Cabang
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Perbarui data cabang mini market MyFanel.
            </p>
        </div>

        <form action="{{ route('branch.update', $branch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Kode Cabang
                    </label>
                    <input
                        type="text"
                        name="code"
                        value="{{ $branch->code }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Nama Cabang
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ $branch->name }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Kota
                    </label>
                    <input
                        type="text"
                        name="city"
                        value="{{ $branch->city }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Telepon
                    </label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ $branch->phone }}"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                    Alamat
                </label>

                <textarea
                    name="address"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white
                           px-4 py-3
                           focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $branch->address }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('branch.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600
                          text-gray-700 dark:text-gray-200
                          hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition">
                    Update Cabang
                </button>

            </div>

        </form>
    </div>
</div>
@endsection