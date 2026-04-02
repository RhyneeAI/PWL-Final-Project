@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 min-h-full">
        <h1 class="text-3xl font-semibold text-gray-800 dark:text-white mb-2">Selamat Datang Kembali, Luhung!</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">Berikut ringkasan aktivitas hari ini.</p>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 - Total Pengguna -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengguna</p>
                <p class="text-4xl font-semibold text-gray-800 dark:text-white mt-2">12,458</p>
                <p class="text-emerald-500 text-sm mt-4 flex items-center gap-x-1">
                    ↑ 12% dari bulan lalu
                </p>
            </div>

            <!-- Card 2 - Total Produk -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                <p class="text-4xl font-semibold text-gray-800 dark:text-white mt-2">974</p>
                <p class="text-emerald-500 text-sm mt-4 flex items-center gap-x-1">
                    ↑ 5,3% dari bulan lalu
                </p>
            </div>

            <!-- Card 3 - Pendapatan -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                <p class="text-5xl font-semibold text-gray-800 dark:text-white mt-3">Rp 2.120.400</p>
                <p class="text-emerald-500 text-sm mt-5 flex items-center gap-x-1">
                    ↑ 5,3% dari bulan lalu
                </p>
            </div>

        </div>

        <div class="mt-10 bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-white">Recent Orders</h2>
                <a href="#" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-x-1">
                    Lihat Semua 
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-400">
                                    Selesai
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7841</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Wayne Murphy</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Smart Watch Series 8</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">26 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 790.060</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-400">
                                    Proses
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7840</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Iriam Mahfedza</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Mechanical Keyboard</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">25 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 80.270</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-400">
                                    Batal
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">#ORD-7840</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Eman Subagja</td>
                            <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">Monitor Xiaomi G24i 144hz</td>
                            <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">27 Mar 2026</td>
                            <td class="px-6 py-5 text-sm font-medium text-right text-gray-900 dark:text-white">Rp 1.619.900</td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900 dark:text-violet-400">
                                    Draft
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    
@endpush