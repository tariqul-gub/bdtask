<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - MultiBranch Accountant</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700|Poppins:400,500,600,700" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: relative;
            overflow: hidden;
        }

        .bg-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(0, 212, 255, 0.1), rgba(9, 132, 227, 0.1));
            animation: float 15s infinite ease-in-out;
        }

        .bg-shapes .shape:nth-child(1) {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
        }

        .bg-shapes .shape:nth-child(2) {
            width: 300px;
            height: 300px;
            bottom: -50px;
            right: -50px;
            animation-delay: -5s;
        }

        .bg-shapes .shape:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 50%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        .register-container {
            display: flex;
            width: 950px;
            max-width: 95%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .register-branding {
            flex: 0.8;
            background: linear-gradient(135deg, #6c5ce7 0%, #0984e3 100%);
            padding: 50px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .register-branding::before {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -80px;
            right: -80px;
        }

        .register-branding::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -40px;
            left: -40px;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .brand-logo svg {
            width: 50px;
            height: 50px;
            fill: white;
        }

        .register-branding h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .register-branding p {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.5;
            max-width: 260px;
        }

        .benefits-list {
            margin-top: 30px;
            text-align: left;
        }

        .benefits-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
            opacity: 0.95;
        }

        .benefits-list li svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            fill: #00d4ff;
        }

        .register-form-section {
            flex: 1.2;
            padding: 45px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-form-section h2 {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .register-form-section .subtitle {
            color: #666;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            fill: #999;
            transition: fill 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #6c5ce7;
            background: white;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
        }

        .form-control.is-invalid {
            border-color: #e74c3c;
            background: #fff5f5;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 11px;
            margin-top: 4px;
            display: block;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6c5ce7 0%, #0984e3 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
        }

        .login-link a {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            color: #0984e3;
        }

        .bd-accent {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .bd-accent span {
            font-size: 11px;
            color: #999;
        }

        .bd-flag {
            width: 22px;
            height: 14px;
            background: #006a4e;
            border-radius: 2px;
            position: relative;
        }

        .bd-flag::before {
            content: '';
            position: absolute;
            width: 9px;
            height: 9px;
            background: #f42a41;
            border-radius: 50%;
            top: 50%;
            left: 45%;
            transform: translate(-50%, -50%);
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                max-width: 420px;
            }

            .register-branding {
                padding: 30px 25px;
            }

            .benefits-list {
                display: none;
            }

            .register-form-section {
                padding: 30px 25px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container">
        <div class="register-branding">
            <div class="brand-logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <h1>নতুন অ্যাকাউন্ট তৈরি করুন</h1>
            <p>বাংলাদেশের সেরা অ্যাকাউন্টিং সফটওয়্যারে আপনাকে স্বাগতম</p>
            <p style="margin-top: 8px; font-size: 11px; opacity: 0.8;">Join Bangladesh's Best Accounting Platform</p>

            <ul class="benefits-list">
                <li>
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    বিনামূল্যে অ্যাকাউন্ট তৈরি
                </li>
                <li>
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    সকল শাখার হিসাব একসাথে
                </li>
                <li>
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    ভ্যাট ও ট্যাক্স কমপ্লায়েন্ট
                </li>
                <li>
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    ২৪/৭ সাপোর্ট
                </li>
            </ul>
        </div>

        <div class="register-form-section">
            <h2>অ্যাকাউন্ট তৈরি করুন 🎉</h2>
            <p class="subtitle">Fill in the details below to create your account</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <input id="name" type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name" 
                               autofocus
                               placeholder="আপনার সম্পূর্ণ নাম">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               placeholder="example@company.com">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Min. 8 characters">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <div class="input-wrapper">
                            <input id="password-confirm" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Confirm password">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    Create Account
                </button>

                <p class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Sign In</a>
                </p>

                <div class="bd-accent">
                    <div class="bd-flag"></div>
                    <span>Made for Bangladesh 🇧🇩</span>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
