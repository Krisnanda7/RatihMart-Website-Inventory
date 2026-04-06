@extends('layouts.app')

@section('content')

<h1 style="font-size:18px;font-weight:600;margin-bottom:16px;">Tambah Barang</h1>

<form action="{{ route('barang.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            {{-- KODE --}}
            <div>
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" class="input">
                @error('kode_barang') <small style="color:red">{{ $message }}</small> @enderror
            </div>

            {{-- NAMA --}}
            <div>
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="input">
            </div>

            {{-- KATEGORI --}}
            <div>
                <label>Kategori</label>
                <input type="text" name="kategori" list="kategori-list" value="{{ old('kategori') }}" class="input">
                <datalist id="kategori-list">
                    @foreach($kategori as $k)
                        <option value="{{ $k }}">
                    @endforeach
                </datalist>
            </div>

            {{-- SATUAN --}}
            <div>
                <label>Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan','pcs') }}" class="input">
            </div>

            {{-- HARGA BELI --}}
            <div>
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" class="input">
            </div>

            {{-- HARGA JUAL --}}
            <div>
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" class="input">
            </div>

            {{-- STOK --}}
            <div>
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok') }}" class="input">
            </div>

            {{-- STOK MIN --}}
            <div>
                <label>Stok Minimum</label>
                <input type="number" name="stok_minimum" value="{{ old('stok_minimum',5) }}" class="input">
            </div>

            {{-- DESKRIPSI --}}
            <div style="grid-column: span 2;">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="input">{{ old('deskripsi') }}</textarea>
            </div>

        </div>
    </div>

    <div style="margin-top:16px;">
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline">Kembali</a>
    </div>

</form>

@endsection

@push('styles')
<style>
.input {
    width:100%;
    padding:7px 10px;
    border:1px solid #ddd;
    border-radius:6px;
    margin-top:4px;
}
label {
    font-size:12px;
    color:var(--text-secondary);
}
</style>
@endpush