<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Poised Technology Admin Login">

    <title>@yield('title', 'Admin Login')</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

</head>

<body class="auth-body">

    <!-- Theme Toggle -->
    <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle
        aria-label="Switch color theme" title="Switch color theme">

        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>

    </button>

    <main class="auth-page">

        <section class="auth-card">

            <!-- Brand -->
            <a class="auth-brand" href="{{ route('admin.dashboard') }}">

                <span class="brand-icon">
                    <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i>
                </span>

                <span>
                    <strong>Poised Admin</strong>
                    <small>Sign in to your admin workspace.</small>
                </span>

            </a>

            <!-- Image -->
            <div class="auth-visual">
                <img src="{{ asset('admin/assets/images/png/dasher-ui-bootstrap-5.jpg') }}"
                    alt="Poised Technology Admin Dashboard">
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="needs-validation" novalidate>

                @csrf

                <div class="mb-4">

                    <p class="eyebrow mb-1">Secure Access</p>

                    <h1 class="h3 mb-1">Admin Login</h1>

                    <p class="text-muted mb-0">
                        Sign in to access the admin dashboard.
                    </p>

                </div>

                <!-- Email -->
                <div class="mb-3">

                    <label class="form-label" for="email">
                        Email Address
                    </label>

                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required>

                    <div class="invalid-feedback">
                        Enter a valid email address.
                    </div>

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Password -->
                <div class="mb-3">

                    <div class="d-flex justify-content-between">

                        <label class="form-label" for="password">
                            Password
                        </label>

                        <a class="small fw-semibold"
                            href="{{ route('admin.password.request') }}">

                            Forgot?

                        </a>

                    </div>

                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        minlength="6"
                        required>

                    <div class="invalid-feedback">
                        Password must be at least 6 characters.
                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Remember Me -->
                <div class="form-check mb-4">

                    <input class="form-check-input"
                        type="checkbox"
                        id="rememberMe"
                        name="remember">

                    <label class="form-check-label" for="rememberMe">
                        Remember me
                    </label>

                </div>

                <!-- Submit -->
                <button class="btn btn-primary w-100" type="submit">

                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>

                    Sign In

                </button>

            </form>

            <!-- Footer -->
            <div class="auth-footer">

                © {{ date('Y') }} Poised Technology Admin Panel

            </div>

        </section>

    </main>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

</body>

</html>