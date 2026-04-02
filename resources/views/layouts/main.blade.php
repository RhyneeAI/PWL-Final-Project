<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    @include('layouts.header')
</head>
<body class="h-full bg-gray-950 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    <!-- Container utama -->
    <div class="flex h-screen overflow-hidden">  
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Content Wrapper -->
            <main id="content-wrapper" class="flex-1 overflow-auto bg-gray-950 p-8">
                <div class="px-4 py-2">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>