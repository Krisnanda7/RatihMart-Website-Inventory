@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('barang.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;margin-bottom:10px;display:inline-block;">← Kembali ke Data Barang</a>
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">{{ $barang->nama_barang }}</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">{{ $barang->kode_barang }}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-primary">Edit Barang</a>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;max-width:800px;">

    {{-- INFO UTAMA --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Informasi Barang</span></div>
        <div class="card-body">
            <table style="width:100%;font-size:13px;border-collapse:collapse;">
                <tr style="border-bottom:1px solid #F5F5F2;">
                    <td style="padding:9px 0;color:var(--text-muted);width:40%;">Kode Barang</td>
                    <td style="padding:9px 0;font-weight:500;color:var(--text-primary);">{{ $barang->kode_barang }}</td>
                </tr>
                <tr style="border-bottom:1px solid #F5F5F2;">
                    <td style="padding:9px 0;color:var(--text-muted);">Nama Barang</td>
                    <td style="padding:9px 0;font-weight:500;color:var(--text-primary);">{{ $barang->nama_barang }}</td>
                </tr>
                <tr style="border-bottom:1px solid #F5F5F2;">
                    <td style="padding:9px 0;color:var(--text-muted);">Kategori</td>
                    <td style="padding:9px 0;">{{ $barang->kategori ?? '—' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #F5F5F2;">
                    <td style="padding:9px 0;color:var(--text-muted);">Satuan</td>
                    <td style="padding:9px 0;">{{ $barang->satuan }}</td>
                </tr>
                <tr>
                    <td style="padding:9px 0;color:var(--text-muted);">Deskripsi</td>
                    <td style="padding:9px 0;color:var(--text-secondary);">{{ $barang->deskripsi ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- HARGA & STOK --}}
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Harga</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div style="background:var(--bg-page);border-radius:var(--radius-md);padding:12px;">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Harga Beli</div>
                        <div style="font-size:16px;font-weight:600;color:var(--text-primary);">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</div>
                    </div>
                    <div style="background:var(--bg-page);border-radius:var(--radius-md);padding:12px;">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Harga Jual</div>
                        <div style="font-size:16px;font-weight:600;color:var(--brand);">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</div>
                    </div>
                </div>
                @php $margin = $barang->harga_jual - $barang->harga_beli; $persen = $barang->harga_beli > 0 ? round(($margin / $barang->harga_beli) * 100) : 0; @endphp
                <div style="margin-top:10px;padding:8px 12px;background:var(--success-bg);border-radius:var(--radius-md);font-size:12px;color:var(--success-text);">
                    Margin: Rp {{ number_format($margin, 0, ',', '.') }} ({{ $persen }}%)
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Stok</span></div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="text-align:center;">
                        <div style="font-size:32px;font-weight:600;color:{{ $barang->stok <= $barang->stok_minimum ? 'var(--danger-text)' : 'var(--text-primary)' }};">{{ $barang->stok }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $barang->satuan }}</div>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;">Stok minimum: <strong>{{ $barang->stok_minimum }}</strong></div>
                        @if($barang->stok <= 0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($barang->stok <= ($barang->stok_minimum / 2))
                            <span class="badge badge-danger">Kritis — segera restock</span>
                        @elseif($barang->stok <= $barang->stok_minimum)
                            <span class="badge badge-warning">Stok Rendah</span>
                        @else
                            <span class="badge badge-success">Stok Aman</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection