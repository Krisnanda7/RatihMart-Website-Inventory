<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Toko Ratih</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F5F5F2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #E8E8E4;
            border-radius: 14px;
            padding: 36px 32px;
            width: 100%;
            max-width: 380px;
        }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand-icon {
            width: 44px; height: 44px;
            background: #185FA5;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }
        .brand-name { font-size: 16px; font-weight: 600; color: #1A1A18; }
        .brand-sub { font-size: 12px; color: #9B9B96; margin-top: 2px; }
        label { display: block; font-size: 12px; font-weight: 500; color: #6B6B67; margin-bottom: 5px; }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #E8E8E4;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: #1A1A18;
            outline: none;
            transition: border-color .15s;
        }
        input:focus { border-color: #185FA5; }
        .field { margin-bottom: 16px; }
        .error { font-size: 11px; color: #A32D2D; margin-top: 4px; }
        .alert-error {
            background: #FCEBEB;
            border: 1px solid #F7C1C1;
            color: #A32D2D;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 16px;
        }
        .remember { display: flex; align-items: center; gap: 7px; margin-bottom: 20px; font-size: 13px; color: #6B6B67; }
        .btn-login {
            width: 100%;
            padding: 10px;
            background: #185FA5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: opacity .12s;
        }
        .btn-login:hover { opacity: .88; }
        .footer-link { text-align: center; margin-top: 18px; font-size: 12px; color: #9B9B96; }
        .footer-link a { color: #185FA5; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-icon">
            <svg width="22" height="22" viewBox="0 0 16 16" fill="none">
                <path d="M2 6l6-4 6 4v7H2V6z" fill="white" opacity=".9"/>
                <rect x="5.5" y="9" width="5" height="4" rx=".5" fill="#185FA5"/>
            </svg>
        </div>
        <div class="brand-name">Toko Ratih</div>
        <div class="brand-sub">Sistem Manajemen Grosir</div>
    </div>

    @if(session('status'))
        <div style="background:#EAF3DE;border:1px solid #C0DD97;color:#3B6D11;padding:10px 12px;border-radius:8px;font-size:12px;margin-bottom:16px;">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="ratih@toko.com"
                style="{{ $errors->has('email') ? 'border-color:#A32D2D;' : '' }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password" required placeholder="••••••••"
                style="{{ $errors->has('password') ? 'border-color:#A32D2D;' : '' }}">
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="remember">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" style="margin:0;cursor:pointer;">Ingat saya</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="margin-left:auto;font-size:12px;color:#185FA5;text-decoration:none;">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-login">Masuk</button>
    </form>

    @if(Route::has('register'))
    <div class="footer-link">
        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
    </div>
    @endif
</div>
</body>
</html>