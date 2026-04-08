@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Profil Saya</h1>
    <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Kelola informasi akun Anda</p>
</div>

<div style="max-width:560px;display:flex;flex-direction:column;gap:14px;">

    {{-- UPDATE NAMA & EMAIL --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Informasi Akun</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    @error('name')
                        <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    @error('email')
                        <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- UPDATE PASSWORD --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Ubah Password</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Password Saat Ini</label>
                    <input type="password" name="current_password"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);outline:none;">
                    @error('current_password', 'updatePassword')
                        <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Password Baru</label>
                    <input type="password" name="password"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);outline:none;">
                    @error('password', 'updatePassword')
                        <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);outline:none;">
                </div>

                <button type="submit" class="btn btn-primary">Ubah Password</button>
            </form>
        </div>
    </div>

    {{-- HAPUS AKUN --}}
    <div class="card" style="border-color:#F7C1C1;">
        <div class="card-header" style="border-color:#F7C1C1;"><span class="card-title" style="color:var(--danger-text);">Hapus Akun</span></div>
        <div class="card-body">
            <p style="font-size:13px;color:var(--text-secondary);margin-bottom:14px;">Setelah akun dihapus, semua data akan hilang permanen dan tidak dapat dipulihkan.</p>
            <form method="POST" action="{{ route('profile.destroy') }}"
                onsubmit="return confirm('Yakin hapus akun? Tindakan ini tidak bisa dibatalkan.')">
                @csrf @method('DELETE')
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Masukkan password untuk konfirmasi</label>
                    <input type="password" name="password"
                        style="width:100%;padding:8px 12px;border:1px solid #F7C1C1;border-radius:var(--radius-md);font-size:13px;font-family:var(--font);outline:none;">
                    @error('password', 'userDeletion')
                        <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-outline" style="color:var(--danger-text);border-color:#F7C1C1;">Hapus Akun Saya</button>
            </form>
        </div>
    </div>

</div>

@endsection