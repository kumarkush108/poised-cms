<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Forgot Password | Poised Technology Admin">

    <title>Forgot Password | Poised Admin</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

    <style>
        .forgot-card {
            max-width: 500px;
            width: 100%;
        }

        .forgot-icon {
            width: 90px;
            height: 90px;
            margin: auto;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .forgot-icon i {
            font-size: 40px;
            color: #0d6efd;
        }
    </style>

</head>

<body class="auth-body">

    <!-- Theme Toggle -->
    <button class="icon-button theme-toggle auth-theme-toggle"
        type="button"
        data-theme-toggle
        aria-label="Switch color theme">

        <i class="bi bi-moon-stars" data-theme-icon></i>

    </button>

    <main class="auth-page">

        <section class="auth-card forgot-card">

            <!-- Brand -->
            <a class="auth-brand mb-4"
                href="{{ route('admin.login') }}">

                <span class="brand-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </span>

                <span>
                    <strong>Poised Admin</strong>
                    <small>Password recovery portal</small>
                </span>

            </a>

            <!-- Icon -->
            <div class="forgot-icon mb-4">
                <i class="bi bi-shield-lock"></i>
            </div>

            <!-- Heading -->
            <div class="text-center mb-4">

                <p class="eyebrow mb-2">
                    Account Recovery
                </p>

                <h1 class="h3 mb-2">
                    Forgot Password?
                </h1>

                <p class="text-muted mb-0">
                    Enter your registered email address and we’ll send you a password reset link.
                </p>

            </div>

            <!-- Success Message -->
            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <!-- Form -->
            <form method="POST"
                action="{{ route('admin.password.email') }}"
                class="needs-validation"
                novalidate>

                @csrf

                <!-- Email -->
                <div class="mb-4">

                    <label class="form-label" for="email">

                        Email Address

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required>

                    </div>

                    @error('email')

                        <div class="text-danger small mt-1">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <!-- Submit -->
                <button type="submit"
                    class="btn btn-primary w-100 py-2">

                    <i class="bi bi-send"></i>

                    Send Reset Link

                </button>

            </form>

            <!-- Footer -->
            <div class="auth-footer mt-4 text-center">

                Remember your password?

                <a href="{{ route('admin.login') }}"
                    class="fw-semibold text-decoration-none">

                    Back to Login

                </a>

            </div>

        </section>

    </main>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

</body>

</html>