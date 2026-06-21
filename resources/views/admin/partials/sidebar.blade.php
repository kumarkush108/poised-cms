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

        <a class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.product-categories.*') ? 'active' : '' }}"
            href="{{ route('admin.products.index') }}">

            <span class="nav-icon">
                <i class="bi bi-box-seam"></i>
            </span>

            <span class="nav-text">Products</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.blog-posts.*') || request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}"
            href="{{ route('admin.blog-posts.index') }}">

            <span class="nav-icon">
                <i class="bi bi-journal-text"></i>
            </span>

            <span class="nav-text">Blog</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.news-articles.*') || request()->routeIs('admin.news-categories.*') ? 'active' : '' }}"
            href="{{ route('admin.news-articles.index') }}">

            <span class="nav-icon">
                <i class="bi bi-newspaper"></i>
            </span>

            <span class="nav-text">News</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.menus.*') || request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
            href="{{ route('admin.menus.index') }}">

            <span class="nav-icon">
                <i class="bi bi-list-nested"></i>
            </span>

            <span class="nav-text">Menus</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}"
            href="{{ route('admin.contact-messages.index') }}">

            <span class="nav-icon">
                <i class="bi bi-envelope"></i>
            </span>

            <span class="nav-text">Contact Messages</span>
        </a>

    </nav>

</aside>