@extends('layouts.main')
@section('title', 'User')

@section('content')
<div class="space-y-6 min-h-full">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">User</h1>
            <p class="text-gray-500 dark:text-gray-400">Kelola semua pengguna di sini.</p>
        </div>

        <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
            + Tambah User
        </button>
    </div>

    <!-- Search -->
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <input type="text" id="search-user" placeholder="Cari user..."
            class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">

                <!-- Head -->
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-white">

                    <tr class="user-row hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-5">Admin</td>
                        <td class="px-6 py-5">admin@gmail.com</td>
                        <td class="px-6 py-5">Admin</td>
                        <td class="px-6 py-5 text-center space-x-2">
                            <button class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs">Edit</button>
                            <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                        </td>
                    </tr>

                    <tr class="user-row hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-5">Tsani</td>
                        <td class="px-6 py-5">tsani@gmail.com</td>
                        <td class="px-6 py-5">Staff</td>
                        <td class="px-6 py-5 text-center space-x-2">
                            <button class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs">Edit</button>
                            <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $('#search-user').on('keyup', function() {
        let value = $(this).val().toLowerCase();

        $('.user-row').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
@endpush