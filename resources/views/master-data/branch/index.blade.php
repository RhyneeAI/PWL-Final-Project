@extends('layouts.main')
@section('title', 'Cabang Toko')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Cabang Toko</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola cabang mini market MyFanel.</p>
            </div>

            <a href="{{ route('branch.create') }}"
             class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
             <i class="fas fa-plus mr-1"></i> Tambah Cabang
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-branch',
            'searchPlaceholder' => 'Cari kode, nama, atau kota cabang...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Bandung', 'column' => 2, 'value' => 'Bandung'],
                ['label' => 'Jakarta', 'column' => 2, 'value' => 'Jakarta'],
                ['label' => 'Aktif', 'column' => 5, 'value' => 'Aktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="branch-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Cabang</th>
                            <th class="px-6 py-4">Kota</th>
                            <th class="px-6 py-4">Telepon</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach ($branches as $branch)
        <tr>
            <td class="px-6 py-4 font-medium">{{ $branch->code }}</td>
            <td class="px-6 py-4">{{ $branch->name }}</td>
            <td class="px-6 py-4">{{ $branch->city }}</td>
            <td class="px-6 py-4">{{ $branch->phone }}</td>
            <td class="px-6 py-4">{{ $branch->address }}</td>
            <td class="px-6 py-4">
                <span class="status-badge {{ $branch->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                    {{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </td>
            <td class="px-6 py-4 text-center whitespace-nowrap">
    <div class="inline-flex items-center justify-center gap-2">

        <a href="{{ route('branch.edit', $branch->id) }}"
           class="btn-action btn-action-edit">
            <i class="fas fa-pen-to-square"></i>
        </a>

        <form action="{{ route('branch.destroy', $branch->id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn-action btn-action-delete">
                <i class="fas fa-trash-can"></i>
            </button>
        </form>

    </div>
</td>
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
        $(function () {
            initMasterDataTable('#branch-table', {
                searchInput: '#search-branch',
                filterButtons: '.table-filter-btn',
            });
        });
    </script>
@endpush
