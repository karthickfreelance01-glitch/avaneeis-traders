<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | AVANEEIS TRADERS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/site.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-wrap">
    <div class="login-brand">
        <img src="{{ asset('assets/images/logo-chicken.jpg') }}" alt="AVANEEIS TRADERS">
        <h2 class="fw-bold mt-4 mb-1">AVANEEIS TRADERS</h2>
        <p class="opacity-75 mb-0">The Complete Poultry Solutions</p>
    </div>

    <div class="login-form">
        <div class="inner">
            <img src="{{ asset('assets/images/logo-full.jpg') }}" alt="AVANEEIS TRADERS" class="mb-4" style="height:64px;">
            <h3 class="fw-bold text-brand-navy mb-1">Welcome back</h3>
            <p class="text-muted mb-4">Sign in to manage products and enquiries.</p>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}" autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-danger btn-lg w-100">Login</button>
            </form>
            <p class="text-center mt-4 mb-0"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i>Back to website</a></p>
        </div>
    </div>
</div>
</body>
</html>
