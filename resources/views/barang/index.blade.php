@extends('layouts.app')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;">Data Barang</h1>
        <p style="font-size:13px;color:var(--text-secondary);">Kelola semua produk toko</p>
    </div>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
</div>

<form method="GET" style="display:flex;gap:10px;margin-bottom:16px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..."
        style="padding:6px 10px;border:1px solid #ddd;border-radius:6px;">

    <select name="kategori" style="padding:6px;border-radius:6px;">
        <option value="">Semua Kategori</option>
        @foreach($kategori as $k)
            <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>
                {{ $k }}
            </option>
        @endforeach
    </select>

    <button class="btn btn-outline">Filter</button>
</form>

<div class="card">
    <div class="card-body" style="overflow:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid #eee;">
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $b)
                <tr style="border-bottom:1px solid #f2f2f2;">
                    <td>{{ $b->kode_barang }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->kategori ?? '-' }}</td>
                    <td>Rp {{ number_format($b->harga_jual,0,',','.') }}</td>
                    <td>{{ $b->stok }}</td>
                    <td>
                        @if($b->status_stok == 'aman')
                            <span class="badge badge-success">Aman</span>
                        @elseif($b->status_stok == 'rendah')
                            <span class="badge badge-warning">Rendah</span>
                        @else
                            <span class="badge badge-danger">Kritis</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('barang.edit', $b->id) }}" class="btn btn-outline">Edit</a>
                        <form action="{{ route('barang.destroy', $b->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus barang ini?')" class="btn btn-outline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:20px;color:gray;">
                        Belum ada data barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:15px;">
            {{ $barang->links() }}
        </div>
    </div>
</div>

@endsection