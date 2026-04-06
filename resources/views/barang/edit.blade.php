@extends('layouts.app')

@section('content')

<h1 style="font-size:18px;font-weight:600;margin-bottom:16px;">Edit Barang</h1>

<form action="{{ route('barang.update', $barang->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            <div>
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang',$barang->kode_barang) }}" class="input">
            </div>

            <div>
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang',$barang->nama_barang) }}" class="input">
            </div>

            <div>
                <label>Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori',$barang->kategori) }}" class="input">
            </div>

            <div>
                <label>Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan',$barang->satuan) }}" class="input">
            </div>

            <div>
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" value="{{ old('harga_beli',$barang->harga_beli) }}" class="input">
            </div>

            <div>
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual',$barang->harga_jual) }}" class="input">
            </div>

            <div>
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok',$barang->stok) }}" class="input">
            </div>

            <div>
                <label>Stok Minimum</label>
                <input type="number" name="stok_minimum" value="{{ old('stok_minimum',$barang->stok_minimum) }}" class="input">
            </div>

            <div style="grid-column: span 2;">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="input">{{ old('deskripsi',$barang->deskripsi) }}</textarea>
            </div>

        </div>
    </div>

    <div style="margin-top:16px;">
        <button class="btn btn-primary">Update</button>
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