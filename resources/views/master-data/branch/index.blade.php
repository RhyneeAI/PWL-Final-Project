@extends('layouts.main')
@section('title', 'Cabang Toko')

@section('content')
<div class="space-y-6 min-h-full">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Cabang Toko</h1>
            <p class="text-gray-500 dark:text-gray-400">Kelola cabang toko di sini.</p>
        </div>

        <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
            + Tambah Cabang
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 uppercase">
                        <th class="px-6 py-4">Nama Cabang</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y text-white">
                    <tr>
                        <td class="px-6 py-4">Cabang Jakarta</td>
                        <td class="px-6 py-4">Jl. Sudirman No. 10</td>
                        <td class="px-6 py-4">08123456789</td>
                        <td class="px-6 py-4 text-center">
                            <button class="bg-blue-500 px-3 py-1 text-xs rounded">Edit</button>
                            <button class="bg-red-500 px-3 py-1 text-xs rounded">Hapus</button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection