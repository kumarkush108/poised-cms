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

        <a class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}"
            href="{{ route('admin.media.index') }}">

            <span class="nav-icon">
                <i class="bi bi-images"></i>
            </span>

            <span class="nav-text">Media Library</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
            href="{{ route('admin.settings.index') }}">

            <span class="nav-icon">
                <i class="bi bi-palette"></i>
            </span>

            <span class="nav-text">Theme Settings</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
            href="{{ route('admin.pages.index') }}">

            <span class="nav-icon">
                <i class="bi bi-file-earmark-text"></i>
            </span>

            <span class="nav-text">Pages</span>
        </a>

        <a class="nav-link" href="#">
            <span class="nav-icon">
                <i class="bi bi-envelope"></i>
            </span>

            <span class="nav-text">Contact Messages</span>
        </a>

    </nav>

</aside>