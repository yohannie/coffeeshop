<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Coffee Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
        /* Custom focus styles */
        .custom-focus:focus {
            outline: 2px solid #6F4E37;
            outline-offset: 2px;
        }
        
        /* Smooth transitions */
        .transition-custom {
            transition: all 0.2s ease-in-out;
        }
        
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #6F4E37;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #5a3d2b;
        }

        /* Skip to main content - accessibility */
        .skip-to-main {
            position: absolute;
            left: -9999px;
            z-index: 999;
            padding: 1em;
            background-color: white;
            color: black;
            opacity: 0;
        }
        
        .skip-to-main:focus {
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <!-- Skip to main content link -->
    <a href="#main-content" class="skip-to-main">
        Skip to main content
    </a>

    <!-- Success/Error Message Toast -->
    <div id="success-message" class="hidden fixed top-4 right-4 z-50 max-w-sm w-full" role="alert" aria-live="polite">
        <div class="rounded-lg p-4 flex items-center justify-between shadow-lg transition-custom">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium"></p>
                </div>
            </div>
            <button type="button" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2">
                <span class="sr-only">Dismiss</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg" role="navigation" aria-label="Main navigation">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-brown-800">Coffee Shop Admin</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('admin.dashboard') }}" 
                            class="border-transparent text-brown-600 hover:border-brown-300 hover:text-brown-800 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-custom custom-focus {{ request()->routeIs('admin.dashboard') ? 'border-brown-500' : '' }}"
                            aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                            class="border-transparent text-brown-600 hover:border-brown-300 hover:text-brown-800 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-custom custom-focus {{ request()->routeIs('admin.users.*') ? 'border-brown-500' : '' }}"
                            aria-current="{{ request()->routeIs('admin.users.*') ? 'page' : 'false' }}">
                            Users
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-brown-600 hover:text-brown-800 hover:bg-brown-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brown-500" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <div class="hidden sm:flex items-center space-x-4">
                    <span class="text-brown-700">Welcome, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-brown-600 hover:text-brown-800 custom-focus">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                    class="text-brown-600 hover:bg-brown-50 hover:text-brown-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-custom {{ request()->routeIs('admin.dashboard') ? 'border-brown-500 bg-brown-50' : 'border-transparent' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" 
                    class="text-brown-600 hover:bg-brown-50 hover:text-brown-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-custom {{ request()->routeIs('admin.users.*') ? 'border-brown-500 bg-brown-50' : 'border-transparent' }}">
                    Users
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="text-brown-600 hover:bg-brown-50 hover:text-brown-800 block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main id="main-content" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" role="main">
        @yield('content')
    </main>

    <script>
        // Add custom Tailwind configuration
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            50: '#fdf8f6',
                            100: '#f2e8e5',
                            200: '#eaddd7',
                            300: '#e0cec7',
                            400: '#d2bab0',
                            500: '#bfa094',
                            600: '#a18072',
                            700: '#977669',
                            800: '#846358',
                            900: '#43302b',
                        },
                    }
                }
            }
        }

        // Setup AJAX CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Mobile menu toggle
        $('.mobile-menu-button').on('click', function() {
            const expanded = $(this).attr('aria-expanded') === 'true';
            $(this).attr('aria-expanded', !expanded);
            $('#mobile-menu').toggleClass('hidden');
        });

        function showMessage(message, isError = false) {
            const messageDiv = $('#success-message');
            const messageContent = messageDiv.find('p');
            const icon = messageDiv.find('svg');
            
            messageDiv.removeClass('hidden');
            messageDiv.children().first().removeClass('bg-green-100 border-green-400 text-green-700 bg-red-100 border-red-400 text-red-700');
            
            if (isError) {
                messageDiv.children().first().addClass('bg-red-100 text-red-700');
                icon.addClass('text-red-400');
            } else {
                messageDiv.children().first().addClass('bg-green-100 text-green-700');
                icon.addClass('text-green-400');
            }
            
            messageContent.text(message);
            
            setTimeout(() => {
                messageDiv.addClass('hidden');
            }, 3000);
        }

        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open modals or menus
                $('#mobile-menu').addClass('hidden');
                $('.mobile-menu-button').attr('aria-expanded', 'false');
            }
        });
    </script>

    @stack('scripts')
</body>
</html> 