@extends('layouts.main')
@section('title', 'Cabang Toko')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Cabang Toko</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola cabang mini market MyFanel.</p>
            </div>

            <a href="{{ route('branches.create') }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Cabang
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-branch',
            'searchPlaceholder' => 'Cari kode, nama, atau alamat cabang...',
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Aktif', 'column' => 4, 'value' => 'Aktif'],
                ['label' => 'Nonaktif', 'column' => 4, 'value' => 'Nonaktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="branch-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Cabang</th>
                            <th class="px-6 py-4">Telepon</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $branch->code }}</td>
                                <td class="px-6 py-4">{{ $branch->name }}</td>
                                <td class="px-6 py-4">{{ $branch->phone ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $branch->address }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $branch->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                                        {{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a href="{{ route('branches.edit', $branch) }}" class="btn-action btn-action-edit" title="Edit">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data cabang.</td>
                            </tr>
                        @endforelse
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
