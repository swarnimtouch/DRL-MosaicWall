<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 40px;
        }
        .login-card .brand {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-control {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            border-radius: 8px;
            padding: 12px 15px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.25);
        }
        .form-control::placeholder { color: rgba(255,255,255,0.4); }
        .form-label { color: rgba(255,255,255,0.75); font-size: 0.875rem; }
        .btn-login {
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: opacity 0.2s;
        }
        .btn-login:hover { opacity: 0.9; color: #fff; }
        .input-group-text {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>

<div class="login-card">

    {{-- Brand --}}
    <div class="brand">
        <i class="fas fa-hospital-alt me-2 text-primary"></i>
        Admin Panel
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:0.875rem">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control"
                       placeholder="admin@admin.com"
                       required autofocus>
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password"
                       name="password"
                       id="passwordField"
                       class="form-control"
                       placeholder="••••••••"
                       required>
                <span class="input-group-text"
                      style="cursor:pointer"
                      onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        {{-- Remember Me --}}
        <div class="mb-4 d-flex align-items-center">
            <input type="checkbox" name="remember" id="remember" class="me-2">
            <label for="remember" class="form-label mb-0">Remember me</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>

    </form>

</div>

<script>
    function togglePassword() {
        const field   = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        if (field.type === 'password') {
            field.type = 'text';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
