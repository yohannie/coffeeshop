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
    </style>
</head>
<body class="bg-coffee min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white p-10 rounded-2xl shadow-lg w-full max-w-md">
        <div class="text-center mb-6">
            <div class="text-5xl text-brown mb-2">☕</div>
            <h1 class="text-2xl font-bold text-brown">Join Coffee Lovers</h1>
            <p class="text-sm text-gray-500">Create your cozy café account</p>
        </div>
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-700">Full Name</label>
                <input name="name" type="text" required value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-brown @error('name') border-red-500 @enderror">
                @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-700">Email</label>
                <input name="email" type="email" required value="{{ old('email') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-brown @error('email') border-red-500 @enderror">
                @error('email') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-700">Password</label>
                <input name="password" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-brown @error('password') border-red-500 @enderror">
                @error('password') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-700">Confirm Password</label>
                <input name="password_confirmation" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-brown">
            </div>
            <button type="submit" class="w-full bg-brown text-white py-2 rounded-md hover:bg-brown-dark transition">Create Account</button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brown font-semibold hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
