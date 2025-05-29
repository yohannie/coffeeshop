<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Coffee Shop') }}</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom Coffee Theme Colors -->
    <style>
      .bg-coffee {
        background-color: #fdf6f0;
      }
      .bg-brown {
        background-color: #6F4E37;
      }
      .hover\:bg-brown-dark:hover {
        background-color: #5a3d2b;
      }
      .text-brown {
        color: #6F4E37;
      }
      .border-brown {
        border-color: #6F4E37;
      }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-brown text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold">☕ Coffee Shop</a>
                </div>
                <div class="flex items-center">
                    @auth
                        <a href="{{ route('user.dashboard') }}" class="hover:bg-brown-dark px-3 py-2 rounded-md">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="ml-3">
                            @csrf
                            <button type="submit" class="hover:bg-brown-dark px-3 py-2 rounded-md">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:bg-brown-dark px-3 py-2 rounded-md">Login</a>
                        <a href="{{ route('register') }}" class="hover:bg-brown-dark px-3 py-2 rounded-md ml-3">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-brown text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Coffee Shop. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 