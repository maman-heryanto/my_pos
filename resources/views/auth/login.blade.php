<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>R3 Jaya POS | Log in</title>

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 90%;
            max-width: 400px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            border: none;
            overflow: hidden;
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
            padding-top: 30px;
            padding-bottom: 0;
            text-align: center;
        }
        .login-logo a {
            color: #764ba2;
            font-weight: 700;
            font-size: 2.2rem;
            text-shadow: none;
        }
        .login-card-body {
            padding: 30px;
            border-radius: 15px;
        }
        .form-control {
            border-radius: 10px;
            height: 50px;
            padding-left: 20px;
            border: 1px solid #eee;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: none;
            background-color: #fff;
        }
        .input-group-text {
            border-radius: 0 10px 10px 0;
            border: 1px solid #eee;
            border-left: none;
            background-color: #f8f9fa;
            color: #aaa;
        }
        .input-group-append .input-group-text {
            border-radius: 10px; /* Fix border radius if standalone */
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .btn-primary {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            height: 50px;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.6);
            background: linear-gradient(to right, #764ba2, #667eea);
        }
        .icheck-primary label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 4px;
        }
        .login-box-msg {
            color: #888;
            padding-bottom: 25px;
        }
        .input-group {
            margin-bottom: 20px !important;
        }
        /* Mobile adjustments */
        @media (max-width: 576px) {
            .login-box {
                margin-top: 0;
                width: 95%;
            }
            .login-logo a {
                font-size: 1.8rem;
            }
        }
        /* Animation */
        .card {
            animation: fadeInUp 0.8s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header text-center">
            <div class="login-logo">
                <a href="#"><b>R3 Jaya</b> POS</a>
            </div>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan masuk untuk memulai sesi Anda</p>

            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 10px; font-size: 0.9rem;">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                Ingat Saya
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary btn-block">Masuk <i class="fas fa-arrow-right ml-2"></i></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
    <div class="text-center mt-3 text-white-50">
        <small>&copy; {{ date('Y') }} R3 Jaya POS System</small>
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>
</body>
</html>
