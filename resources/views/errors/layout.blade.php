<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MyFanel') }} | @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="h-full bg-gray-950 flex items-center justify-center">
    <div class="w-full max-w-md px-6 text-center">

        <!-- Logo -->
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-3xl mb-6">
            <span class="text-white font-bold text-2xl">MF</span>
        </div>

        <!-- Error Code -->
        <p class="text-7xl font-bold text-gray-700 mb-2">@yield('code')</p>

        <!-- Icon -->
        <div class="text-5xl mb-4 @yield('icon-color')">
            <i class="@yield('icon')"></i>
        </div>

        <!-- Message -->
        <h1 class="text-2xl font-semibold text-white mb-2">@yield('heading')</h1>
        <p class="text-gray-400 text-sm mb-8">@yield('description')</p>

        <!-- Action -->
        @yield('action')

        <p class="text-gray-600 text-xs mt-8">&copy; {{ date('Y') }} MyFanel.</p>
    </div>
</body>
</html>
