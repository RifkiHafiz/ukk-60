@extends('layouts.app')
@section('content')
<div>
    <style>
        :root {
            --primary-blue: #0ea5e9;
            --blue-600: #0284c7;
            --blue-700: #0369a1;
            --blue-900: #0c4a6e;
            --light-blue: #38bdf8;
            --ultra-light-blue: #e0f2fe;
            --white: #ffffff;
            --gray-100: #f1f5f9;
            --gray-600: #475569;
            --gray-800: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--gray-800);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(14, 165, 233, 0.1);
            padding: 20px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-blue) !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
        }

        .navbar-brand span {
            color: var(--blue-700);
        }

        .nav-link {
            color: var(--gray-600) !important;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-blue) !important;
        }

        .btn-nav {
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin-left: 15px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-login {
            background: transparent;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-login:hover {
            background: var(--primary-blue);
            color: white;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary-blue), var(--blue-700));
            color: white;
            border: none;
        }

        .btn-register:hover {
            opacity: 0.9;
        }

        .hero-section {
            min-height: 85vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--ultra-light-blue) 0%, var(--white) 100%);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--blue-900);
            margin-bottom: 25px;
            line-height: 1.2;
        }

        .hero-title .highlight {
            color: var(--primary-blue);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--gray-600);
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, var(--primary-blue), var(--blue-700));
            color: white;
        }

        .btn-primary-hero:hover {
            opacity: 0.9;
            color: white;
        }

        .btn-secondary-hero {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-secondary-hero:hover {
            background: var(--primary-blue);
            color: white;
        }

        .hero-image {
            text-align: center;
        }

        .hero-image img {
            max-width: 80%;
            height: auto;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--blue-900);
            margin-bottom: 15px;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--gray-600);
            text-align: center;
            margin-bottom: 60px;
        }

        .how-section {
            padding: 80px 0;
            background: var(--ultra-light-blue);
        }

        .step-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            height: 100%;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-blue), var(--blue-700));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin: 0 auto 20px;
        }

        .step-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--blue-900);
            margin-bottom: 12px;
        }

        .step-description {
            color: var(--gray-600);
            line-height: 1.7;
        }

        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-blue), var(--blue-700));
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 35px;
            opacity: 0.95;
        }

        .btn-cta {
            background: white;
            color: var(--primary-blue);
            padding: 15px 45px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            opacity: 0.95;
        }

        .footer {
            background: var(--blue-900);
            color: white;
            padding: 40px 0;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 25px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .footer-brand img {
            height: 40px;
            width: auto;
        }

        .footer-description {
            opacity: 0.9;
            max-width: 600px;
            line-height: 1.7;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-link:hover {
            background: var(--primary-blue);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 35px;
            padding-top: 25px;
            text-align: center;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .btn-nav {
                margin-left: 0;
                margin-top: 10px;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .navbar-brand img {
                height: 35px;
            }
        }
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="">
                <img src="{{ asset('storage/img/logo-BorrowMe.png') }}" alt="BorrowMe Logo">
                <div><span>Borrow</span>Me</div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        @if (Auth::user())
                            <a href="{{ route('dashboard') }}" class="btn-nav btn-login">Login</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-nav btn-login">Login</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if (Auth::user())
                            <a href="{{ route('dashboard') }}" class="btn-nav btn-register">Register</a>
                        @else
                            <a href="{{ route('register.page') }}" class="btn-nav btn-register">Register</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="hero-title">
                        Borrow Equipment <br>
                        <span class="highlight">Anytime, Anywhere</span>
                    </h1>
                    <p class="hero-subtitle">
                        Simplify your equipment borrowing process with BorrowMe. Request, track, and manage equipment loans seamlessly in one powerful platform.
                    </p>
                    <div>
                        @if (Auth::user())
                            <a href="{{ route('dashboard') }}" class="btn-hero btn-primary-hero">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register.page') }}" class="btn-hero btn-primary-hero">Get Started Free</a>
                        @endif
                        <a href="#how-it-works" class="btn-hero btn-secondary-hero">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="{{ asset('storage/img/logo-pinjam.png') }}" alt="Logo Peminjaman Barang">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-section" id="how-it-works">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">
                Getting started with BorrowMe is simple and easy
            </p>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Account</h3>
                        <p class="step-description">
                            Sign up for free and complete your profile in minutes.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Browse Equipment</h3>
                        <p class="step-description">
                            Explore available equipment and check availability.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Submit Request</h3>
                        <p class="step-description">
                            Fill out a simple form with your borrowing details.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3 class="step-title">Get Equipment</h3>
                        <p class="step-description">
                            Once approved, pick up your equipment right away.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready to Get Started?</h2>
            <p class="cta-description">
                Join thousands of users enjoying seamless equipment borrowing today!
            </p>
            <a href="{{ route('register.page') }}" class="btn-cta">Create Free Account</a>
        </div>
    </section>

    <!-- Footer - Simplified -->
    <footer class="footer" id="about">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="{{ asset('storage/img/logo-BorrowMe.png') }}" alt="BorrowMe Logo">
                    BorrowMe
                </div>
                
                <p class="footer-description">
                    The smart way to manage equipment borrowing. Simplifying access to tools and resources for everyone.
                </p>
                
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 BorrowMe. All rights reserved. Made in Indonesia</p>
            </div>
        </div>
    </footer>
</div>
@endsection