@php
$isAdmin = auth()->guard('admin')->check();
$isUser = auth()->guard('web')->check();

$authUser = $isAdmin
? auth()->guard('admin')->user()
: auth()->guard('web')->user();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('user.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link
                        :href="$isAdmin ? route('admin.dashboard') : route('user.dashboard')"
                        :active="request()->routeIs($isAdmin ? 'admin.dashboard' : 'user.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (! $isAdmin)


                    <x-nav-link
                        :href="$isAdmin ? route('product.index') : route('user.product')"
                        :active="request()->routeIs($isAdmin ? 'product.*' : 'user.product')">
                        {{ __('Products') }}
                    </x-nav-link>

                    <x-nav-link
                        :href="route('user.myorder')"
                        :active="request()->routeIs('user.myorder')">
                        {{ __('My Orders') }}
                    </x-nav-link>
                    @endif
                </div>
                @if ($isAdmin)

                @can('view permissions')
                <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">
                    {{ __('Permissions') }}
                </x-nav-link>
                @endcan

                @can('view roles')
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                    {{ __('Roles') }}
                </x-nav-link>
                @endcan

                @can('view users')
                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Users') }}
                </x-nav-link>
                @endcan

                @can('view products')
                <x-nav-link :href="route('product.index')" :active="request()->routeIs('product.*')">
                    {{ __('Products') }}
                </x-nav-link>
                @endcan

                @can('view category')
                <x-nav-link :href="route('category.index')" :active="request()->routeIs('category.*')">
                    {{ __('Category') }}
                </x-nav-link>
                @endcan

                @can('view cms')
                <x-nav-link :href="route('section.index')" :active="request()->routeIs('section.*')">
                    {{ __('CMS') }}
                </x-nav-link>
                @endcan

                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            <div>
                                {{ $authUser->name ?? 'User' }}

                                @if ($isAdmin)
                                ({{ $authUser->roles->pluck('name')->implode(', ') ?: 'No Role' }})
                                @endif
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST"
                            action="{{ $isAdmin ? route('admin.logout') : route('user.logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="$isAdmin ? route('admin.logout') : route('user.logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link
                :href="$isAdmin ? route('admin.dashboard') : route('user.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if ($isAdmin)
            @can('view permissions')
            <x-responsive-nav-link :href="route('permissions.index')">
                {{ __('Permissions') }}
            </x-responsive-nav-link>
            @endcan

            @can('view roles')
            <x-responsive-nav-link :href="route('roles.index')">
                {{ __('Roles') }}
            </x-responsive-nav-link>
            @endcan

            @can('view users')
            <x-responsive-nav-link :href="route('users.index')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            @endcan
            @endif
        </div>
    </div>
</nav>