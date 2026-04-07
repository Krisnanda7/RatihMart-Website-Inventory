@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('barang.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:10px;">
        ← Kembali ke Data Barang
    </a>
    <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Edit Barang</h1>
    <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">{{ $barang->kode_barang }} — {{ $barang->nama_barang }}</p>
</div>

<div style="max-width:700px;">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('barang.update', $barang) }}">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Kode Barang <span style="color:var(--danger-text);">*</span></label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;{{ $errors->has('kode_barang') ? 'border-color:var(--danger-text);' : '' }}">
                        @error('kode_barang')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Nama Barang <span style="color:var(--danger-text);">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;{{ $errors->has('nama_barang') ? 'border-color:var(--danger-text);' : '' }}">
                        @error('nama_barang')
                            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori', $barang->kategori) }}"
                            list="kategori-list"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                        <datalist id="kategori-list">
                            @foreach($kategori as $kat)
                                <option value="{{ $kat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Satuan <span style="color:var(--danger-text);">*</span></label>
                        <select name="satuan"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;background:var(--bg-white);">
                            @foreach(['pcs','ktn','dos','bks','botol','slop','kg','ltr'] as $sat)
                                <option value="{{ $sat }}" {{ old('satuan', $barang->satuan) == $sat ? 'selected' : '' }}>{{ $sat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Harga Beli (Rp) <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="harga_beli" value="{{ old('harga_beli', $barang->harga_beli) }}"
                            min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Harga Jual (Rp) <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}"
                            min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Stok Saat Ini <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}"
                            min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Stok Minimum <span style="color:var(--danger-text);">*</span></label>
                        <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $barang->stok_minimum) }}"
                            min="0"
                            style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" rows="3"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;resize:vertical;">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                </div>

                <div style="display:flex;gap:10px;border-top:var(--border);padding-top:16px;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection