@extends('layouts.main')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Tambah Kategori</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan kategori produk baru untuk semua cabang.</p>
        </div>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        placeholder="Makanan"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="is_active" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        <option value="1" @selected(old('is_active', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active') === '0')>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    placeholder="Deskripsi kategori..."
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('categories.index') }}" class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Batal</a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
