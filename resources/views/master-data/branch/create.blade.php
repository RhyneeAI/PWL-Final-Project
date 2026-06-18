@extends('layouts.main')

@section('title', 'Tambah Cabang')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">
                Tambah Cabang
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tambahkan data cabang baru MyFanel.
            </p>
        </div>

        <form action="{{ route('branch.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Kode Cabang
                    </label>
                    <input
                        type="text"
                        name="code"
                        placeholder="Contoh: BR-003"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Nama Cabang
                    </label>
                    <input
                        type="text"
                        name="name"
                        placeholder="MyFanel Bandung"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Kota
                    </label>
                    <input
                        type="text"
                        name="city"
                        placeholder="Bandung"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                        Telepon
                    </label>
                    <input
                        type="text"
                        name="phone"
                        placeholder="022-1234567"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">
                    Alamat
                </label>

                <textarea
                    name="address"
                    rows="4"
                    placeholder="Masukkan alamat lengkap cabang..."
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-white
                           placeholder-gray-400 dark:placeholder-gray-500
                           px-4 py-3
                           focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
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
                    class="px-5 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600 transition">
                    Simpan Cabang
                </button>

            </div>

        </form>
    </div>
</div>
@endsection