<nav class="navbar admin-navbar navbar-expand bg-white">

    <div class="container-fluid px-3 px-lg-4">

        <button class="sidebar-toggle" type="button" data-sidebar-toggle>

            <span></span>
            <span></span>
            <span></span>

        </button>

        <div class="ms-auto">

            <div class="dropdown">

                <button class="profile-button dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown">

                    <img class="avatar-img avatar-sm"
                         src="{{ asset('admin/assets/images/avatar/avatar.jpg') }}">

                    <span class="profile-name d-none d-sm-inline">
                        Admin
                    </span>

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="#">
                            Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            Settings
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>