@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('stock-movements.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;margin-bottom:10px;display:inline-block;">← Kembali ke Manajemen Stok</a>
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Tambah Penyesuaian Stok</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Catat stok masuk atau keluar sebagai transaksi inventaris.</p>
        </div>
    </div>
</div>

<div class="card" style="max-width:560px;">
    <div class="card-body">
        <form action="{{ route('stock-movements.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:16px;">
                <label for="barang_id" style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Barang</label>
                <select id="barang_id" name="barang_id" required style="width:100%;padding:11px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;">
                    <option value="">Pilih barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }} — {{ $barang->kode_barang }}
                        </option>
                    @endforeach
                </select>
                @error('barang_id')<div style="color:var(--danger-text);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                    <label for="direction" style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Jenis</label>
                    <select id="direction" name="direction" required style="width:100%;padding:11px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;">
                        <option value="">Pilih jenis</option>
                        <option value="in" {{ old('direction') === 'in' ? 'selected' : '' }}>Masuk</option>
                        <option value="out" {{ old('direction') === 'out' ? 'selected' : '' }}>Keluar</option>
                    </select>
                    @error('direction')<div style="color:var(--danger-text);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="qty" style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Jumlah</label>
                    <input id="qty" name="qty" type="number" min="1" value="{{ old('qty', 1) }}" required style="width:100%;padding:11px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;" />
                    @error('qty')<div style="color:var(--danger-text);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-bottom:18px;">
                <label for="notes" style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Catatan</label>
                <textarea id="notes" name="notes" rows="4" style="width:100%;padding:11px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;">{{ old('notes') }}</textarea>
                @error('notes')<div style="color:var(--danger-text);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan Penyesuaian</button>
        </form>
    </div>
</div>

@endsection
