@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 min-h-full">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white mb-2">
                Selamat Datang Kembali, {{ auth()->user()->name }}!
            </h1>
            <p class="text-gray-500 dark:text-gray-400">Berikut ringkasan aktivitas hari ini.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Order</p>
                <p class="text-4xl font-semibold text-gray-800 dark:text-white mt-2">1.248</p>
                <p class="text-emerald-500 text-sm mt-4 flex items-center gap-x-1">
                    <i class="fas fa-arrow-up text-xs"></i> 8,2% dari bulan lalu
                </p>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                <p class="text-4xl font-semibold text-gray-800 dark:text-white mt-2">974</p>
                <p class="text-emerald-500 text-sm mt-4 flex items-center gap-x-1">
                    <i class="fas fa-arrow-up text-xs"></i> 5,3% dari bulan lalu
                </p>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                <p class="text-5xl font-semibold text-gray-800 dark:text-white mt-3">Rp 2.120.400</p>
                <p class="text-emerald-500 text-sm mt-5 flex items-center gap-x-1">
                    <i class="fas fa-arrow-up text-xs"></i> 5,3% dari bulan lalu
                </p>
            </div>
        </div>

        <!-- Charts -->
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-semibold text-lg text-gray-800 dark:text-white">Ringkasan Penjualan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pendapatan dan jumlah order</p>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3">
                    <div class="flex gap-2">
                        <button type="button" class="chart-period-btn table-filter-btn is-active px-4 py-2 text-sm rounded-xl" data-period="month">
                            Per Bulan
                        </button>
                        <button type="button" class="chart-period-btn table-filter-btn px-4 py-2 text-sm rounded-xl" data-period="year">
                            Per Tahun
                        </button>
                    </div>

                    <select id="chart-filter-year" class="chart-filter-select" aria-label="Filter tahun"></select>

                    <select id="chart-filter-month" class="chart-filter-select" aria-label="Filter bulan">
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Pendapatan</h3>
                        <span id="revenue-chart-subtitle" class="text-xs text-gray-500 dark:text-gray-400">Per hari · Juni 2026</span>
                    </div>
                    <div class="h-72">
                        <canvas id="revenue-chart"></canvas>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Jumlah Order</h3>
                        <span id="orders-chart-subtitle" class="text-xs text-gray-500 dark:text-gray-400">Per hari · Juni 2026</span>
                    </div>
                    <div class="h-72">
                        <canvas id="orders-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-white">Order Terbaru</h2>
                @if (auth()->user()->role->canViewTransactions())
                    <a href="{{ route('transaction.index') }}" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-x-1">
                        Lihat Semua
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7842</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Xylo Forbatz</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Wireless Headphone</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">27 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 1.250.070</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-400">Selesai</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7841</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Wayne Murphy</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Smart Watch Series 8</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">26 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 790.060</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-400">Proses</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7840</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Iriam Mahfedza</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Mechanical Keyboard</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">25 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 80.270</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-400">Batal</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7839</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Eman Subagja</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Monitor Xiaomi G24i 144Hz</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">27 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 1.619.900</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900 dark:text-violet-400">Draft</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="/assets/js/dashboard-charts.js"></script>
@endpush
