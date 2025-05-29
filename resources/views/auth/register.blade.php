<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Coffee Shop</title>
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
        .auth-background {
            background-image: url('https://images.unsplash.com/photo-1442512595331-e89e73853f31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .auth-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="auth-background min-h-screen flex items-center justify-center font-sans relative">
    <div class="glass-effect p-10 rounded-2xl shadow-2xl w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <div class="text-6xl text-brown mb-3">☕</div>
            <h1 class="text-3xl font-bold text-brown mb-2">Join Our Coffee Club</h1>
            <p class="text-sm text-gray-600">Create your account and start your coffee journey</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input name="name" type="text" required value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown focus:border-brown transition-all @error('name') border-red-500 @enderror"
                       placeholder="Enter name">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input name="email" type="email" required value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown focus:border-brown transition-all @error('email') border-red-500 @enderror"
                       placeholder="Enter email.com">
                @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input name="password" type="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown focus:border-brown transition-all @error('password') border-red-500 @enderror"
                       placeholder="••••••••">
                @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input name="password_confirmation" type="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown focus:border-brown transition-all"
                       placeholder="••••••••">
            </div>
            <button type="submit" 
                    class="w-full bg-brown text-white py-3 rounded-lg hover:bg-brown-dark transition-colors duration-200 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown">
                Create Account
            </button>
        </form>
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-brown font-semibold hover:text-brown-dark transition-colors duration-200">Sign in here</a>
            </p>
        </div>
    </div>
</body>
</html>
