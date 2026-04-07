@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('barang.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:10px;">
        ← Kembali ke Data Barang
    </a>
    <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Tambah Barang Baru</h1>
    <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Isi semua informasi barang yang akan ditambahkan ke inventaris</p>
</div>

<div style="max-width:700px;">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('barang.store') }}">
                @csrf

                {{-- KODE & NAMA --}}
                <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Kode Barang <span style="color:var(--danger-text);">*</span></label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                            placeholder="SBK-001"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;{{ $errors->has('kode_barang') ? 'border-color:var(--danger-text);' : '' }}">
                        @error('kode_barang')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Nama Barang <span style="color:var(--danger-text);">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                            placeholder="Minyak Goreng Bimoli 2L"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;{{ $errors->has('nama_barang') ? 'border-color:var(--danger-text);' : '' }}">
                        @error('nama_barang')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- KATEGORI & SATUAN --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori') }}"
                            placeholder="Sembako, Minuman, Snack..."
                            list="kategori-list"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                        <datalist id="kategori-list">
                            @foreach($kategori as $kat)
                                <option value="{{ $kat }}">
                            @endforeach
                            <option value="Sembako">
                            <option value="Mie Instan">
                            <option value="Minuman">
                            <option value="Snack">
                            <option value="Produk Rumah">
                            <option value="Rokok">
                        </datalist>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Satuan <span style="color:var(--danger-text);">*</span></label>
                        <select name="satuan"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;background:var(--bg-white);">
                            @foreach(['pcs','ktn','dos','bks','botol','slop','kg','ltr'] as $sat)
                                <option value="{{ $sat }}" {{ old('satuan') == $sat ? 'selected' : '' }}>{{ $sat }}</option>
                            @endforeach
                        </select>
                        @error('satuan')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- HARGA --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Harga Beli (Rp) <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="harga_beli" value="{{ old('harga_beli') }}"
                            placeholder="0" min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;{{ $errors->has('harga_beli') ? 'border-color:var(--danger-text);' : '' }}">
                        @error('harga_beli')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Harga Jual (Rp) <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual') }}"
                            placeholder="0" min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;{{ $errors->has('harga_jual') ? 'border-color:var(--danger-text);' : '' }}">
                        @error('harga_jual')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- STOK --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Stok Awal <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', 0) }}"
                            placeholder="0" min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                        @error('stok')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">
                            Stok Minimum <span style="color:var(--danger-text);">*</span>
                            <span style="font-weight:400;color:var(--text-muted);">(batas alert)</span>
                        </label>
                        <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 5) }}"
                            placeholder="5" min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                        @error('stok_minimum')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- DESKRIPSI --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" rows="3"
                        placeholder="Catatan tambahan tentang barang ini..."
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;resize:vertical;">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- TOMBOL --}}
                <div style="display:flex;gap:10px;border-top:var(--border);padding-top:16px;">
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection