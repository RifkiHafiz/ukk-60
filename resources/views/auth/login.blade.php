@extends('layouts.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            height: 100vh;
            display: flex;
            position: relative;
        }

        .welcome-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: white;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
        }

        .welcome-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            z-index: 2;
            position: relative;
        }

        .welcome-section p {
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 500px;
            z-index: 2;
            position: relative;
            opacity: 0.95;
        }

        .decoration {
            position: absolute;
            border-radius: 20px;
            opacity: 0.25;
        }

        .decoration-1 {
            width: 220px;
            height: 220px;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            top: 15%;
            left: 10%;
            transform: rotate(45deg);
            animation: float 6s ease-in-out infinite;
        }

        .decoration-2 {
            width: 160px;
            height: 160px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            bottom: 25%;
            left: 15%;
            transform: rotate(30deg);
            animation: float 4s ease-in-out infinite 1s;
        }

        .decoration-3 {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            top: 45%;
            left: 45%;
            transform: rotate(15deg);
            animation: float 5s ease-in-out infinite 2s;
        }

        .decoration-4 {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            bottom: 40%;
            left: 25%;
            transform: rotate(60deg);
            animation: float 4.5s ease-in-out infinite 1.5s;
        }

        .decoration-line {
            position: absolute;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
            border-radius: 2px;
        }

        .line-1 {
            width: 180px;
            top: 30%;
            left: 15%;
            transform: rotate(-45deg);
            animation: slideRight 3s ease-in-out infinite;
        }

        .line-2 {
            width: 220px;
            top: 40%;
            left: 25%;
            transform: rotate(-30deg);
            animation: slideRight 4s ease-in-out infinite 1s;
        }

        .line-3 {
            width: 140px;
            top: 55%;
            left: 20%;
            transform: rotate(-60deg);
            animation: slideRight 3.5s ease-in-out infinite 2s;
        }

        .line-4 {
            width: 160px;
            bottom: 30%;
            left: 30%;
            transform: rotate(-20deg);
            animation: slideRight 3.8s ease-in-out infinite 0.5s;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: pulse 4s ease-in-out infinite;
        }

        .circle-1 {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 50%;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 60px;
            height: 60px;
            bottom: 35%;
            left: 40%;
            animation-delay: 1s;
        }

        .circle-3 {
            width: 80px;
            height: 80px;
            top: 60%;
            left: 55%;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(45deg);
            }
            50% {
                transform: translateY(-25px) rotate(55deg);
            }
        }

        @keyframes slideRight {
            0%, 100% {
                transform: translateX(0) rotate(-45deg);
                opacity: 0;
            }
            50% {
                opacity: 0.7;
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.2;
            }
        }

        .login-section {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            color: #1e3a8a;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .user-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 20px rgba(59, 130, 246, 0.4);
        }

        .user-icon i {
            color: white;
            font-size: 2.5rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #1e3a8a;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            height: 50px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding-left: 45px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
            background-color: white;
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            bottom: 10px;
            color: #3b82f6;
            font-size: 1.2rem;
        }

        .form-check {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .form-check-label {
            color: #64748b;
            font-size: 0.9rem;
        }

        .forgot-password {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: #1e40af;
        }

        .btn-login {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            border: none;
            border-radius: 25px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #64748b;
            font-size: 0.95rem;
        }

        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #1e40af;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-error i {
            color: #ef4444;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .alert-error ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .alert-error ul li {
            color: #dc2626;
            font-size: 1rem;
            font-weight: 500;
        }

        .input-error {
            border-color: #ef4444 !important;
        }

        .input-error:focus {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.15) !important;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .welcome-section {
                flex: 0;
                padding: 40px 30px;
                min-height: 35vh;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }

            .welcome-section p {
                font-size: 0.95rem;
            }

            .login-section {
                flex: 1;
                padding: 30px 20px;
            }

            .decoration,
            .circle {
                display: none;
            }
        }
    </style>

    <div class="login-container">
        <div class="welcome-section">
            <div class="decoration decoration-1"></div>
            <div class="decoration decoration-2"></div>
            <div class="decoration decoration-3"></div>
            <div class="decoration decoration-4"></div>

            <div class="decoration-line line-1"></div>
            <div class="decoration-line line-2"></div>
            <div class="decoration-line line-3"></div>
            <div class="decoration-line line-4"></div>

            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>

            <h1>Welcome to BorrowMe</h1>
            <p>Sign in to your account and continue your journey with us. Access all your borrowing and lending activities in one secure place. Let's get started!</p>
        </div>

        <div class="login-section">
            <div class="login-box">
                <div class="login-header">
                    <div class="user-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h2>LOGIN</h2>
                    <p>Welcome back! Please login to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}" onsubmit="showLoading()">
                    @csrf

                    @if ($errors->any())
                        <div class="alert-error">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="align-self-center">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'input-error' : '' }}" id="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'input-error' : '' }}" id="password" placeholder="Enter your password" minlength="8" required>
                    </div>

                    <button type="submit" class="btn btn-login">Login</button>

                    <div class="register-link">
                        Don't have an account? <a href="{{ route('register.page') }}">Register here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
