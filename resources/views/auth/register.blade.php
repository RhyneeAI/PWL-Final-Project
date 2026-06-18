<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | MyFanel</title>
    <script src="/assets/js/theme.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

    <section class="grid text-center min-h-screen items-center p-8">
        <div>
            <button id="theme-toggle" type="button" title="Ganti tema" class="fixed top-6 right-6 p-3 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:scale-110 transition-all duration-300 shadow-lg z-50">
                <i id="theme-toggle-icon" class="fas fa-sun text-lg"></i>
            </button>

            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent mb-2">Sign Up</h1>
            <p class="mb-8 text-gray-600 dark:text-gray-400 font-normal text-lg">
                Buat akun baru untuk mengakses MyFanel
            </p>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="mx-auto max-w-md text-left">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"></i>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700
                                   bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition-all duration-200"
                        >
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"></i>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="username"
                            autocomplete="username"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700
                                   bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition-all duration-200"
                        >
                    </div>
                    @error('username')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email <span class="text-gray-400 font-normal">(untuk profil)</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"></i>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="name@mail.com"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700
                                   bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition-all duration-200"
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="********"
                            class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-300 dark:border-gray-700
                                   bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition-all duration-200"
                        >
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                            <i id="eyeIcon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"></i>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="********"
                            class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-300 dark:border-gray-700
                                   bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition-all duration-200"
                        >
                        <button type="button" id="togglePasswordConfirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                            <i id="eyeIconConfirmation" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                                               text-white font-semibold py-3 px-4 rounded-xl
                                               transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]
                                               shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40
                                               flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-user-plus text-sm"></i>
                    <span>Daftar</span>
                </button>

                <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">
                        Sign in
                    </a>
                </p>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('theme-toggle').addEventListener('click', () => window.MyFanelTheme.toggleTheme());
        window.MyFanelTheme.applyTheme(window.MyFanelTheme.getTheme());

        function setupPasswordToggle(buttonId, inputId, iconId) {
            const button = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            button.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        setupPasswordToggle('togglePassword', 'password', 'eyeIcon');
        setupPasswordToggle('togglePasswordConfirmation', 'password_confirmation', 'eyeIconConfirmation');
    </script>
</body>
</html>
