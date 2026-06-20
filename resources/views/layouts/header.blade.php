<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'MyFanel') }} | @yield('title', 'Home')</title>
<script src="/assets/js/theme.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = { darkMode: 'class' };
</script>
<link rel="stylesheet" href="/assets/css/admin.css">
@stack('styles')
