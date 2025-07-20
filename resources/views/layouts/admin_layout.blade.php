<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $adminSettings['siteName'] ?? 'Admin Panel' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        {{-- Sidebar --}}
        <div class="w-64 bg-slate-800 text-white flex flex-col">
            <div class="p-4 border-b border-slate-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center overflow-hidden">
                        @if($adminSettings['logo'])
                            <img src="{{ $adminSettings['logo'] }}" alt="Logo" class="w-full h-full object-cover" />
                        @else
                            <i data-lucide="wifi" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">{{ $adminSettings['siteName'] ?? 'MYHOTSPOT' }}</h1>
                        <p class="text-xs text-slate-400">Admin Panel</p>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ $authUser ? str_replace('_', ' ', ucwords($authUser->role)) : 'User' }}</p>
                        <p class="text-xs text-slate-400">{{ $authUser ? $authUser->username : '...' }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4">
                <ul class="space-y-2">
                    @php
                        $menuItems = [
                            ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'path' => route('admin.dashboard')],
                            ['icon' => 'users', 'label' => 'Social Users', 'path' => route('admin.social_users')],
                            ['icon' => 'user-check', 'label' => 'Members', 'path' => route('admin.members')],
                            ['icon' => 'router', 'label' => 'Router Config', 'path' => route('admin.router_config')],
                            ['icon' => 'settings', 'label' => 'Settings', 'path' => route('admin.settings')],
                            ['icon' => 'shield', 'label' => 'Permissions', 'path' => route('admin.permissions')],
                            ['icon' => 'user', 'label' => 'Profile', 'path' => route('admin.profile')],
                        ];
                    @endphp

                    @foreach($menuItems as $item)
                        <li>
                            <a href="{{ $item['path'] }}"
                               class="w-full flex items-center space-x-3 px-4 py-2 rounded-lg transition-colors
                               {{ request()->url() === $item['path'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"
                            >
                                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="p-4 border-t border-slate-700">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center space-x-3 px-4 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-colors"
                    >
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input
                                type="text"
                                placeholder="Search..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            />
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button class="relative p-2 text-gray-600 hover:text-gray-800">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $authUser ? str_replace('_', ' ', ucwords($authUser->role)) : 'User' }}</p>
                                <p class="text-xs text-gray-500">{{ $authUser ? $authUser->username : '...' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>