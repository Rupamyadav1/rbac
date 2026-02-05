<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
            <h1 class="font-bold text-xl">Company</h1>

            <div class="space-x-6 flex items-center">
                @foreach ( DB::table('sections')
                ->where('status', 1)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->get() as $section)
                <a href="{{ ($section->redirect_to && Route::has($section->redirect_to)) 
            ? route($section->redirect_to) 
            : '#' }}" class="hover:text-blue-600">{{ $section->title }}</a>
                @endforeach



              @php
    $isAdmin = auth()->guard('admin')->check();
    $isUser  = auth()->guard('web')->check();
@endphp

@if ($isAdmin)
    <!-- Admin Dashboard Link -->
    <a href="{{ route('admin.dashboard') }}"
       class="text-gray-700 hover:text-black font-medium">
        🛡 Admin Dashboard
    </a>

@elseif ($isUser)
    <!-- User Dashboard Link -->
    <a href="{{ route('user.dashboard') }}"
       class="text-gray-700 hover:text-black font-medium">
        👤 Dashboard
    </a>

@else
    <!-- Guest: Login / Register Dropdown -->
    <x-dropdown align="right" width="56">
        <x-slot name="trigger">
            <button
                class="inline-flex items-center gap-1 text-gray-700 hover:text-black font-medium">
                Login / Register
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            <!-- User -->
            <x-dropdown-link :href="route('login')">
                👤 User Login
            </x-dropdown-link>

            <x-dropdown-link :href="route('register')">
                📝 User Register
            </x-dropdown-link>

            <div class="border-t my-1"></div>

            <!-- Admin -->
            <x-dropdown-link :href="route('admin.login')">
                🛡 Admin Login
            </x-dropdown-link>

            <x-dropdown-link :href="route('admin.register')">
                📝 Admin Register
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>
@endif


            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white text-center py-6 mt-10">
        © {{ date('Y') }} Company
    </footer>

</body>

</html>