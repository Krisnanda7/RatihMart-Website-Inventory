<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Toko Ratih</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --brand: #185FA5; --brand-light: #E6F1FB;
            --sidebar-width: 220px; --topbar-height: 52px;
            --font: 'Plus Jakarta Sans', sans-serif;
            --radius-sm: 6px; --radius-md: 8px; --radius-lg: 12px;
            --border: 1px solid #E8E8E4;
            --bg-page: #F5F5F2; --bg-white: #FFFFFF; --bg-hover: #F0F0EC;
            --text-primary: #1A1A18; --text-secondary: #6B6B67; --text-muted: #9B9B96;
            --success-bg: #EAF3DE; --success-text: #3B6D11;
            --warning-bg: #FAEEDA; --warning-text: #854F0B;
            --danger-bg:  #FCEBEB; --danger-text:  #A32D2D;
            --info-bg:    #E6F1FB; --info-text:    #185FA5;
        }
        body { font-family: var(--font); background: var(--bg-page); color: var(--text-primary); font-size: 14px; line-height: 1.5; display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: var(--bg-white); border-right: var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; }
        .sidebar-brand { padding: 18px 16px 14px; border-bottom: var(--border); }
        .brand-logo { display: flex; align-items: center; gap: 9px; }
        .brand-icon { width: 30px; height: 30px; background: var(--brand); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
        .brand-name { font-size: 14px; font-weight: 600; }
        .brand-sub  { font-size: 11px; color: var(--text-muted); margin-top: 2px; padding-left: 39px; }
        .nav-section { padding: 10px 0; }
        .nav-section + .nav-section { border-top: var(--border); }
        .nav-label { font-size: 10px; font-weight: 600; color: var(--text-muted); letter-spacing: .07em; text-transform: uppercase; padding: 4px 16px 6px; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: 7px 16px; font-size: 13px; color: var(--text-secondary); text-decoration: none; border-left: 2px solid transparent; transition: background .12s, color .12s; }
        .nav-item:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-item.active { color: var(--brand); background: var(--brand-light); border-left-color: var(--brand); font-weight: 500; }
        .nav-badge { margin-left: auto; background: var(--danger-bg); color: var(--danger-text); font-size: 10px; font-weight: 600; padding: 1px 6px; border-radius: 10px; }
        .sidebar-footer { margin-top: auto; border-top: var(--border); padding: 10px 16px; }
        .user-avatar { width: 30px; height: 30px; border-radius: 50%; background: var(--brand-light); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: var(--brand); flex-shrink: 0; }
        .main-wrapper { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { height: var(--topbar-height); background: var(--bg-white); border-bottom: var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; position: sticky; top: 0; z-index: 50; }
        .topbar-breadcrumb { font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
        .topbar-breadcrumb .current { color: var(--text-primary); font-weight: 500; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-date { font-size: 12px; color: var(--text-muted); }
        .page-content { padding: 22px 24px; flex: 1; }
        .card { background: var(--bg-white); border: var(--border); border-radius: var(--radius-lg); }
        .card-body { padding: 16px 18px; }
        .card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: var(--border); }
        .card-title { font-size: 13px; font-weight: 600; }
        .card-action { font-size: 12px; color: var(--brand); text-decoration: none; font-weight: 500; }
        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .badge-success { background: var(--success-bg); color: var(--success-text); }
        .badge-warning { background: var(--warning-bg); color: var(--warning-text); }
        .badge-danger  { background: var(--danger-bg);  color: var(--danger-text); }
        .badge-info    { background: var(--info-bg);    color: var(--info-text); }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 500; font-family: var(--font); cursor: pointer; border: var(--border); text-decoration: none; transition: opacity .12s; }
        .btn-primary { background: var(--brand); color: #fff; border-color: var(--brand); }
        .btn-primary:hover { opacity: .88; }
        .btn-outline { background: var(--bg-white); color: var(--text-secondary); }
        .btn-outline:hover { background: var(--bg-hover); }
        .alert { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: var(--radius-md); font-size: 12.5px; margin-bottom: 18px; }
        .alert-warning { background: var(--warning-bg); color: var(--warning-text); border: 1px solid #FAC775; }
        .alert-danger  { background: var(--danger-bg);  color: var(--danger-text);  border: 1px solid #F7C1C1; }
        .alert-success { background: var(--success-bg); color: var(--success-text); border: 1px solid #C0DD97; }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M2 6l6-4 6 4v7H2V6z" fill="white" opacity=".9"/>
                    <rect x="5.5" y="9" width="5" height="4" rx=".5" fill="#185FA5"/>
                </svg>
            </div>
            <span class="brand-name">Toko Ratih</span>
        </div>
        <div class="brand-sub">Sistem Manajemen Grosir</div>
    </div>
    <nav>
        <div class="nav-section">
            <div class="nav-label">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1" y="1" width="6" height="6" rx="1.2" fill="currentColor" opacity=".9"/><rect x="9" y="1" width="6" height="6" rx="1.2" fill="currentColor" opacity=".5"/><rect x="1" y="9" width="6" height="6" rx="1.2" fill="currentColor" opacity=".5"/><rect x="9" y="9" width="6" height="6" rx="1.2" fill="currentColor" opacity=".5"/></svg>
                Dashboard
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Penjualan</div>
            <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M2 3h12M2 8h12M2 13h7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                Daftar Transaksi
            </a>
            <a href="{{ route('transaksi.create') }}" class="nav-item">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="2" y="2" width="12" height="12" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 8h6M8 5v6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                Buat Transaksi
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Inventaris</div>
            <a href="{{ route('barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1" y="5" width="14" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 5V3.5A2.5 2.5 0 0 1 8 1v0a2.5 2.5 0 0 1 3 2.5V5" stroke="currentColor" stroke-width="1.3"/></svg>
                Data Barang
                @if(isset($stockAlertCount) && $stockAlertCount > 0)
                    <span class="nav-badge">{{ $stockAlertCount }}</span>
                @endif
            </a>
        </div>
    </nav>
    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:9px;padding:6px 0;">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'R', 0, 2)) }}</div>
            <div>
                <div style="font-size:13px;font-weight:500;">{{ Auth::user()->name ?? 'Ratih' }}</div>
                <div style="font-size:11px;color:var(--text-muted);">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:4px;">
            @csrf
            <button type="submit" style="display:flex;align-items:center;gap:9px;width:100%;background:none;border:none;cursor:pointer;font-family:var(--font);font-size:13px;color:var(--text-secondary);padding:6px 0;">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M6 2H3a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h3M10 11l3-3-3-3M13 8H6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-breadcrumb">
            <span>Toko Ratih</span>
            <span>›</span>
            <span class="current">{{ $title ?? 'Dashboard' }}</span>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">✕ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>