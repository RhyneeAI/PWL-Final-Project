@extends('layouts.main')
@section('title', 'Supplier')

@php($canManage = auth()->user()->role->canManageSuppliers())

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Supplier</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    @if ($canManage)
                        Kelola data supplier untuk stok masuk barang.
                    @else
                        Lihat daftar supplier yang tersedia untuk stok masuk.
                    @endif
                </p>
            </div>

            @if ($canManage)
                <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                    + Tambah Supplier
                </button>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <input type="text" id="search-supplier" placeholder="Cari supplier..."
                class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Supplier</th>
                            <th class="px-6 py-4">Telepon</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Status</th>
                            @if ($canManage)
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-white">
                        @foreach ([
                            ['SUP-001', 'PT Sumber Makmur', '022-1112233', 'sumber@supplier.com', 'Aktif'],
                            ['SUP-002', 'CV Fresh Food', '022-4445566', 'fresh@supplier.com', 'Aktif'],
                            ['SUP-003', 'UD Berkah Jaya', '021-7778899', 'berkah@supplier.com', 'Aktif'],
                        ] as [$kode, $nama, $telepon, $email, $status])
                            <tr class="supplier-row hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-5">{{ $kode }}</td>
                                <td class="px-6 py-5">{{ $nama }}</td>
                                <td class="px-6 py-5">{{ $telepon }}</td>
                                <td class="px-6 py-5">{{ $email }}</td>
                                <td class="px-6 py-5">
                                    <span class="px-2 py-1 text-xs rounded-full bg-emerald-500/10 text-emerald-400">{{ $status }}</span>
                                </td>
                                @if ($canManage)
                                    <td class="px-6 py-5 text-center space-x-2">
                                        <button class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs">Edit</button>
                                        <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#search-supplier').on('keyup', function() {
            let value = $(this).val().toLowerCase();

            $('.supplier-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
@endpush
