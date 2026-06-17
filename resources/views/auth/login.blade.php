<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | MyFanel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Custom dark mode override */
        .dark {
            background-color: #0f172a;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

    <section class="grid text-center h-screen items-center p-8">
        <div>
            <!-- Dark mode toggle button -->
            <button id="darkModeToggle" class="fixed top-6 right-6 p-3 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:scale-110 transition-all duration-300 shadow-lg z-50">
                <i id="darkModeIcon" class="fas fa-moon text-lg"></i>
            </button>

            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent mb-2">Sign In</h1>
            <p class="mb-8 text-gray-600 dark:text-gray-400 font-normal text-lg">
                Enter your email and password to sign in
            </p>

            {{-- Flash success --}}
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Flash error --}}
            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="mx-auto max-w-md text-left">
                @csrf
                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Your Email
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

                <!-- Password Field -->
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

                <!-- Sign In Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
                                               text-white font-semibold py-3 px-4 rounded-xl
                                               transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]
                                               shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40
                                               flex items-center justify-center gap-2 mt-6">
                    <i class="fas fa-sign-in-alt text-sm"></i>
                    <span>Sign In</span>
                </button>

                <!-- Forgot Password -->
                <div class="mt-4 flex justify-end">
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Register Link -->
                <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    Not registered? 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">
                        Create account
                    </a>
                </p>
            </form>
        </div>
    </section>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }
        
        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            document.body.classList.toggle('dark');
            
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
            
            // Toggle icon
            if (isDark) {
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            } else {
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
            }
        });
        
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>