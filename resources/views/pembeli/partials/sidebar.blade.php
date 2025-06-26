<div class="p-3">
    <h4 class="mb-3">Menu Pembeli</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembeli.dashboard') }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembeli.menu.index') }}">
                <i class="fas fa-utensils me-2"></i> Daftar Menu
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembeli.cart.index') }}">
                <i class="fas fa-shopping-cart me-2"></i> Keranjang
            </a>
        </li>
    </ul>
</div>
