<!-- components/sidebar.html -->
@php($role = auth()->user()->role)

<aside id="sidebar" 
       class="w-64 bg-white dark:bg-gray-900 
              border-r border-gray-200 dark:border-gray-700 
              flex flex-col h-screen transition-all duration-300 flex-shrink-0">

    <!-- Top Sidebar (Logo) -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center gap-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-3xl flex items-center justify-center text-white font-bold text-xl flex-shrink-0">MF</div>
            <span id="sidebar-logo-text" class="menu-text font-semibold text-xl tracking-tight text-gray-800 dark:text-white transition-all">My Fanel</span>
        </div>
    </div>

    <!-- Menu Area -->
    <div class="flex-1 overflow-y-auto p-6" id="sidebar-menu">
        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" data-page="dashboard" class="admin-menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            @if ($role->canManageBranches() || $role->canManageProducts() || $role->canManageCategories() || $role->canManageUsers())
                <!-- Master Data -->
                <div class="text-xs font-semibold text-gray-400 uppercase mt-4 mb-2">Master Data</div>

                @if ($role->canManageBranches())
                    <a href="{{ route('branch.index') }}" data-page="branches" class="admin-menu-item">
                        <i class="fas fa-store"></i>
                        <span class="menu-text">Cabang Toko</span>
                    </a>
                @endif

                @if ($role->canManageProducts())
                    <a href="{{ route('product.index') }}" data-page="products" class="admin-menu-item">
                        <i class="fas fa-boxes"></i>
                        <span class="menu-text">Produk</span>
                    </a>
                @endif

                @if ($role->canManageCategories())
                    <a href="{{ route('category.index') }}" data-page="categories" class="admin-menu-item">
                        <i class="fas fa-tags"></i>
                        <span class="menu-text">Kategori</span>
                    </a>
                @endif

                @if ($role->canManageUsers())
                    <a href="{{ route('user.index') }}" data-page="users" class="admin-menu-item">
                        <i class="fas fa-users"></i>
                        <span class="menu-text">Pengguna</span>
                    </a>
                @endif
            @endif

            @if ($role->canViewTransactions() || $role->canManageStock())
                <!-- Transaksi -->
                <div class="text-xs font-semibold text-gray-400 uppercase mt-4 mb-2">Transaksi</div>

                @if ($role->canViewTransactions())
                    <a href="{{ route('transaction.index') }}" data-page="orders" class="admin-menu-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="menu-text">Penjualan</span>
                    </a>
                @endif

                @if ($role->canManageStock())
                    <a href="{{ route('stock-mutation.index') }}" data-page="stock-in" class="admin-menu-item">
                        <i class="fas fa-truck-loading"></i>
                        <span class="menu-text">Stok Masuk</span>
                    </a>
                    <a href="#" data-page="stock-out" class="admin-menu-item">
                        <i class="fas fa-dolly"></i>
                        <span class="menu-text">Stok Keluar</span>
                    </a>
                @endif
            @endif

            @if ($role->canPrintReport())
                <!-- Laporan -->
                <div class="text-xs font-semibold text-gray-400 uppercase mt-4 mb-2">Laporan</div>
                <a href="{{ route('report.index') }}" data-page="reports" class="admin-menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-text">Laporan</span>
                </a>
            @endif

            @if ($role->canManageSettings())
                <!-- Pengaturan -->
                <div class="text-xs font-semibold text-gray-400 uppercase mt-4 mb-2">Pengaturan</div>
                <a href="{{ route('settings.index') }}" data-page="settings" class="admin-menu-item">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Pengaturan</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Bottom Sidebar -->
    <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex-shrink-0 text-xs text-gray-500 dark:text-gray-400">
        <span id="sidebar-version" class="transition-all">v1.2.3 • My Fanel</span>
    </div>
</aside>
