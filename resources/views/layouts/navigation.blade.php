<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Logo & Navigation -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isPenjual() ? route('penjual.dashboard') : route('pembeli.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dashboard -->
                    <x-nav-link :href="auth()->user()->isPenjual() ? route('penjual.dashboard') : route('pembeli.dashboard')"
                                :active="request()->routeIs('penjual.dashboard') || request()->routeIs('pembeli.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isPenjual())
                        <x-nav-link :href="route('penjual.dashboard')" :active="request()->routeIs('penjual.menu.*')">
                            {{ __('Kelola Menu') }}
                        </x-nav-link>
                        <x-nav-link :href="route('penjual.orders')" :active="request()->routeIs('penjual.orders.*')">
                            {{ __('Pesanan') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('pembeli.cart.index')" :active="request()->routeIs('pembeli.cart.*')">
                            {{ __('Keranjang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pembeli.orders.index')" :active="request()->routeIs('pembeli.orders.*')">
                            {{ __('Pesanan Saya') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right: User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user me-2"></i>{{ __('Profile') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('profile.password.edit')">
                            <i class="fas fa-key me-2"></i>{{ __('Ubah Password') }}
                        </x-dropdown-link>
                        
                        @if(auth()->user()->isPenjual())
                            <x-dropdown-link :href="route('profile.qris.edit')">
                                <i class="fas fa-qrcode me-2"></i>{{ __('Kelola QRIS') }}
                            </x-dropdown-link>
                        @endif

                        <hr class="my-1">

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>{{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none" aria-label="Toggle navigation menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="auth()->user()->isPenjual() ? route('penjual.dashboard') : route('pembeli.dashboard')"
                                   :active="request()->routeIs('penjual.dashboard') || request()->routeIs('pembeli.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isPenjual())
                <x-responsive-nav-link :href="route('penjual.dashboard')" :active="request()->routeIs('penjual.menu.*')">
                    {{ __('Kelola Menu') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.orders')" :active="request()->routeIs('penjual.orders.*')">
                    {{ __('Pesanan') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('pembeli.cart.index')" :active="request()->routeIs('pembeli.cart.*')">
                    {{ __('Keranjang') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pembeli.orders.index')" :active="request()->routeIs('pembeli.orders.*')">
                    {{ __('Pesanan Saya') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user me-2"></i>{{ __('Profile') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.password.edit')">
                    <i class="fas fa-key me-2"></i>{{ __('Ubah Password') }}
                </x-responsive-nav-link>
                
                @if(auth()->user()->isPenjual())
                    <x-responsive-nav-link :href="route('profile.qris.edit')">
                        <i class="fas fa-qrcode me-2"></i>{{ __('Kelola QRIS') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
