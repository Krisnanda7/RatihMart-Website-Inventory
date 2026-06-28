<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Toko Ratih</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #185FA5 0%, #C8DFF0 100%);
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
        .brand-sub  { font-size: 12px; color: #9B9B96; margin-top: 2px; }
        label { display: block; font-size: 12px; font-weight: 500; color: #6B6B67; margin-bottom: 5px; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #E8E8E4;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: #1A1A18;
            outline: none;
            transition: border-color .15s, background-color .15s;
            background: #F9F9F7;
        }
        input:focus { border-color: #185FA5; }
        .field { margin-bottom: 16px; }
        .error { font-size: 11px; color: #A32D2D; margin-top: 4px; }
        .btn-register {
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
            margin-top: 4px;
        }
        .btn-register:hover { opacity: .88; }
        .form-bg {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 12px;
            border: 1px solid #E0E0E0;
        }
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
        <div class="brand-sub">Buat akun baru</div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-bg">
            <div class="field">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    placeholder="Ratih"
                    style="{{ $errors->has('name') ? 'border-color:#A32D2D;' : '' }}">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="ratih@toko.com"
                    style="{{ $errors->has('email') ? 'border-color:#A32D2D;' : '' }}">
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Min. 8 karakter"
                    style="{{ $errors->has('password') ? 'border-color:#A32D2D;' : '' }}">
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password">
            </div>
        </div>

        <button type="submit" class="btn-register">Buat Akun</button>
    </form>

    <div class="footer-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </div>
</div>
</body>
</html>