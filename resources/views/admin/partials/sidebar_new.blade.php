<div class="d-flex flex-column p-4">
    <!-- Logo/Brand -->
    <div class="text-center mb-4">
        <h3 class="text-white font-weight-bold">
            <i class="fas fa-utensils me-2"></i>NganTeen
        </h3>
        <p class="text-white-50 small mb-0">Admin Panel</p>
    </div>
    
    <!-- Navigation -->
    <ul class="nav flex-column">
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
               href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" 
               href="{{ route('admin.orders') }}">
                <i class="fas fa-shopping-cart me-2"></i>Orders
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.warungs*') ? 'active' : '' }}" 
               href="{{ route('admin.warungs') }}">
                <i class="fas fa-store me-2"></i>Warungs
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.payment.settings') ? 'active' : '' }}" 
               href="{{ route('admin.payment.settings') }}">
                <i class="fas fa-credit-card me-2"></i>Payment Settings
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.settlements*') ? 'active' : '' }}" 
               href="{{ route('admin.settlements') }}">
                <i class="fas fa-handshake me-2"></i>Settlements
                @php
                    $unsettledCount = \App\Models\GlobalOrder::where('payment_status', 'paid')
                                                            ->where('is_settled', false)
                                                            ->count();
                @endphp
                @if($unsettledCount > 0)
                    <span class="badge bg-warning ms-2">{{ $unsettledCount }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
               href="{{ route('admin.users.index') }}">
                <i class="fas fa-users me-2"></i>User Management
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.food-news*') ? 'active' : '' }}" 
               href="{{ route('admin.food-news.index') }}">
                <i class="fas fa-newspaper me-2"></i>Food News
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}" 
               href="{{ route('admin.transactions') }}">
                <i class="fas fa-money-bill-wave me-2"></i>Transactions
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('admin.payouts*') ? 'active' : '' }}" 
               href="{{ route('admin.payouts') }}">
                <i class="fas fa-wallet me-2"></i>Payouts
            </a>
        </li>
    </ul>
    
    <!-- User Info -->
    <div class="mt-auto pt-3 border-top border-light">
        <div class="text-white-50 small">
            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
        </div>
        <div class="text-white-50 small">Admin Access</div>
    </div>
</div>
