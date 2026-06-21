@extends('layouts.main')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6 min-h-full">
    @include('partials.session-alert')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Laporan Penjualan</h1>
            <p class="text-gray-500 dark:text-gray-400">Rekap transaksi penjualan per periode.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('report.pdf', request()->query()) }}"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('report.excel', request()->query()) }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('report.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
            </div>
            @if ($canSelectBranch)
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cabang</label>
                    <select name="branch_id"
                        class="rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
                        <option value="">Semua Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected((int) $branchId === $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm">
                    <i class="fas fa-filter mr-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- Table(s) --}}
    @if ($groupByBranch && $grouped)
        @foreach ($grouped as $branchName => $branchTransactions)
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 px-1">
                    <i class="fas fa-store mr-2 text-blue-500"></i>
                    {{ $branchName }}
                </h2>
                @include('reports._table', ['transactions' => $branchTransactions, 'canSelectBranch' => false, 'grandTotal' => null])
            </div>
        @endforeach
    @else
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            @include('reports._table', ['transactions' => $paginated, 'canSelectBranch' => $canSelectBranch, 'grandTotal' => $grandTotal])
        </div>
    @endif

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $paginated->firstItem() }}–{{ $paginated->lastItem() }} dari {{ $paginated->total() }} transaksi
        </p>
        @include('partials.pagination', ['paginator' => $paginated])
    </div>
</div>
@endsection