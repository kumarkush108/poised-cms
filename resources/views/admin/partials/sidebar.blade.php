<aside class="admin-sidebar" id="adminSidebar">

    <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('admin.dashboard') }}">
            <span class="brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>

            <span class="brand-copy">
                <span class="brand-title">Poised Admin</span>
                <span class="brand-subtitle">CMS Dashboard</span>
            </span>
        </a>
    </div>

    <nav class="sidebar-nav">

        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">

            <span class="nav-icon">
                <i class="bi bi-speedometer2"></i>
            </span>

            <span class="nav-text">Dashboard</span>
        </a>

        <a class="nav-link" href="#">
            <span class="nav-icon">
                <i class="bi bi-house"></i>
            </span>

            <span class="nav-text">Home CMS</span>
        </a>

        <a class="nav-link" href="#">
            <span class="nav-icon">
                <i class="bi bi-info-circle"></i>
            </span>

            <span class="nav-text">About CMS</span>
        </a>

        <a class="nav-link" href="#">
            <span class="nav-icon">
                <i class="bi bi-box"></i>
            </span>

            <span class="nav-text">Solutions CMS</span>
        </a>

        <a class="nav-link" href="#">
            <span class="nav-icon">
                <i class="bi bi-briefcase"></i>
            </span>

            <span class="nav-text">Services CMS</span>
        </a>

        <a class="nav-link" href="#">
            <span class="nav-icon">
                <i class="bi bi-envelope"></i>
            </span>

            <span class="nav-text">Contact Messages</span>
        </a>

    </nav>

</aside>