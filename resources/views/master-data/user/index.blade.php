@extends('layouts.main')
@section('title', 'Pengguna')

@include('partials.master-data.datatables-assets')

@section('content')
    <div class="space-y-6 min-h-full">
        @include('partials.session-alert')

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Pengguna</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola semua pengguna sistem di sini.</p>
            </div>

            <a href="{{ route('users.create') }}"
            class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Pengguna
            </a>
        </div>

        @include('partials.master-data.table-toolbar', [
            'searchId' => 'search-user',
            'searchPlaceholder' => 'Cari nama, username, email, atau cabang...',
            'branchFilterId' => 'filter-user-branch',
            'branches' => $branches,
            'headOfficeFilter' => true,
            'roleFilterId' => 'filter-user-role',
            'roleFilterOptions' => $roleFilterOptions,
            'filters' => [
                ['label' => 'Semua', 'column' => '', 'value' => ''],
                ['label' => 'Aktif', 'column' => 5, 'value' => 'Aktif'],
                ['label' => 'Nonaktif', 'column' => 5, 'value' => 'Nonaktif'],
            ],
        ])

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table id="user-table" class="master-data-table w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Cabang</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->branchLabel() }}</td>
                                <td class="px-6 py-4">{{ $user->username }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4" data-order="{{ $user->role->listOrder() }}">{{ $user->role->label() }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $user->is_active ? 'status-badge-active' : 'status-badge-inactive' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if ($user->canBeManagedBy(auth()->user()))
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                            class="btn-action btn-action-edit">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>

                                            @if (auth()->id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn-action btn-action-delete">
                                                        <i class="fas fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data pengguna.</td>
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
            initMasterDataTable('#user-table', {
                searchInput: '#search-user',
                filterButtons: '.table-filter-btn',
                branchFilter: {
                    select: '#filter-user-branch',
                    column: 1,
                },
                roleFilter: {
                    select: '#filter-user-role',
                    column: 4,
                },
                order: [[4, 'asc'], [0, 'asc']],
            });
        });
    </script>
@endpush
