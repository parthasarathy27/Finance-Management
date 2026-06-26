<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FinTrack Admin</title>
    
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Style -->
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #f1f5f9;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #6366f1;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
            color: #fff;
        }

        .btn-login {
            background-color: #6366f1;
            border-color: #6366f1;
            color: #fff;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .brand-logo {
            font-size: 2.5rem;
            color: #818cf8;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .brand-title {
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
        }

        .alert-custom {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .info-box {
            background-color: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            color: #c7d2fe;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div class="brand-title">FinTrack Admin</div>


        @if($errors->any())
            <div class="alert alert-custom p-3 mb-3">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label text-light">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-white-50 text-white-50" style="border-radius: 8px 0 0 8px; border: 1px solid rgba(255,255,255,0.1);"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0" style="border-radius: 0 8px 8px 0;" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label text-light">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-white-50 text-white-50" style="border-radius: 8px 0 0 8px; border: 1px solid rgba(255,255,255,0.1);"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0" style="border-radius: 0 8px 8px 0;" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-white-50" for="remember" style="font-size: 0.9rem;">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-login mb-3">Sign In</button>
            
            <div class="text-center">
                <span class="text-white-50" style="font-size: 0.9rem;">New account?</span>
                <a href="{{ route('register') }}" class="text-white font-weight-bold" style="text-decoration: none; font-size: 0.9rem;"> Create Admin</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
