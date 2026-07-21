<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Reset Password | Poised Technology Admin">

    <title>Reset Password | Poised Admin</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

    <style>
        .reset-card {
            max-width: 500px;
            width: 100%;
        }

        .reset-icon {
            width: 90px;
            height: 90px;
            margin: auto;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-icon i {
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

        <section class="auth-card reset-card">

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
            <div class="reset-icon mb-4">
                <i class="bi bi-key"></i>
            </div>

            <!-- Heading -->
            <div class="text-center mb-4">

                <p class="eyebrow mb-2">
                    Account Recovery
                </p>

                <h1 class="h3 mb-2">
                    Reset Password
                </h1>

                <p class="text-muted mb-0">
                    Enter your new password below.
                </p>

            </div>

            <!-- Broker error (invalid / expired token) -->
            @if ($errors->has('email') && !$errors->has('password'))

                <div class="alert alert-danger">

                    {{ $errors->first('email') }}

                </div>

            @endif

            <!-- Form -->
            <form method="POST"
                action="{{ route('admin.password.update') }}"
                class="needs-validation"
                novalidate>

                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                @error('token')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <!-- Email -->
                <div class="mb-3">

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
                            value="{{ old('email', $email ?? '') }}"
                            required>

                    </div>

                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <!-- New Password -->
                <div class="mb-3">

                    <label class="form-label" for="password">
                        New Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="At least 8 characters"
                            minlength="8"
                            required>

                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <!-- Confirm Password -->
                <div class="mb-4">

                    <label class="form-label" for="password_confirmation">
                        Confirm New Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>

                        <input type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Repeat new password"
                            minlength="8"
                            required>

                    </div>

                </div>

                <!-- Submit -->
                <button type="submit"
                    class="btn btn-primary w-100 py-2">

                    <i class="bi bi-check-circle"></i>

                    Reset Password

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
