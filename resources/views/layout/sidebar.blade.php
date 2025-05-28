<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <div class="navbar-brand">
        <button class="btn-menu ls-toggle-btn" type="button">
            <i class="zmdi zmdi-menu"></i>
        </button>
        <a href="{{ route('home') }}">
            <img src="../assets/images/logo.svg" width="25" alt="Aero">
            <span class="m-l-10">Aero</span>
        </a>
    </div>
    <div class="menu">
        <ul class="list">
            <li>
                <div class="user-info">
                    <div class="image">
                        <a href="#"><img src="../assets/images/profile_av.jpg" alt="User"></a>
                    </div>
                    <div class="detail">
                        <h4>Michael</h4>
                        <small>Super Admin</small>
                    </div>
                </div>
            </li>            

            <li class="{{ Request::segment(1) === 'dashboard' ? 'active open' : null }}">
                <a href="{{ route('home') }}">
                    <i class="zmdi zmdi-home"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="{{ Request::segment(1) === 'my-profile' ? 'active open' : null }}">
                <a href="{{ route('home') }}">
                    <i class="zmdi zmdi-account"></i><span>My Profile</span>
                </a>
            </li>

            <li class="{{ Request::segment(1) === 'produk' ? 'active open' : null }}">
                <a href="{{ route('produk.index') }}">
                    <i class="zmdi zmdi-store"></i><span>Produk</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
