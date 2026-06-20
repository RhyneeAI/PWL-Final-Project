@extends('layouts.main')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Edit Kategori</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Perbarui data kategori produk.</p>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                        placeholder="Makanan"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="is_active" class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                        <option value="1" @selected(old('is_active', $category->is_active ? '1' : '0') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active', $category->is_active ? '1' : '0') == '0')>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    placeholder="Deskripsi kategori..."
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('categories.index') }}" class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Batal</a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600">Update Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
