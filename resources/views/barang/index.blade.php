@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Data Barang</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Total {{ $barang->total() }} barang terdaftar</p>
    </div>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
        Tambah Barang
    </a>
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:12px 18px;">
        <form method="GET" action="{{ route('barang.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau kode barang..."
                style="flex:1;min-width:200px;padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);outline:none;color:var(--text-primary);background:var(--bg-white);"
            >
            <select name="kategori" style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);background:var(--bg-white);outline:none;">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
            <select name="filter" style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);background:var(--bg-white);outline:none;">
                <option value="">Semua Stok</option>
                <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding:7px 16px;">Cari</button>
            @if(request()->hasAny(['search','kategori','filter']))
                <a href="{{ route('barang.index') }}" class="btn btn-outline" style="padding:7px 16px;">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="border-bottom:var(--border);">
                    <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Kode</th>
                    <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Nama Barang</th>
                    <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Kategori</th>
                    <th style="padding:11px 18px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Harga Jual</th>
                    <th style="padding:11px 18px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Stok</th>
                    <th style="padding:11px 18px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Status</th>
                    <th style="padding:11px 18px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $b)
                <tr style="border-bottom:1px solid #F5F5F2;transition:background .1s;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background=''">
                    <td style="padding:12px 18px;color:var(--text-muted);font-size:12px;font-weight:500;">{{ $b->kode_barang }}</td>
                    <td style="padding:12px 18px;">
                        <div style="font-weight:500;color:var(--text-primary);">{{ $b->nama_barang }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $b->satuan }}</div>
                    </td>
                    <td style="padding:12px 18px;">
                        @if($b->kategori)
                            <span style="background:#F0F0EC;color:var(--text-secondary);padding:2px 8px;border-radius:4px;font-size:11px;font-weight:500;">{{ $b->kategori }}</span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="padding:12px 18px;text-align:right;font-weight:500;color:var(--text-primary);">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                    <td style="padding:12px 18px;text-align:center;">
                        <span style="font-weight:600;color:{{ $b->stok <= $b->stok_minimum ? 'var(--danger-text)' : 'var(--text-primary)' }};">
                            {{ number_format($b->stok) }}
                        </span>
                        <span style="font-size:11px;color:var(--text-muted);"> / min {{ $b->stok_minimum }}</span>
                    </td>
                    <td style="padding:12px 18px;text-align:center;">
                        @if($b->stok <= 0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($b->stok <= ($b->stok_minimum / 2))
                            <span class="badge badge-danger">Kritis</span>
                        @elseif($b->stok <= $b->stok_minimum)
                            <span class="badge badge-warning">Rendah</span>
                        @else
                            <span class="badge badge-success">Aman</span>
                        @endif
                    </td>
                    <td style="padding:12px 18px;text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('barang.show', $b) }}" class="btn btn-outline" style="padding:4px 10px;font-size:12px;">Detail</a>
                            <a href="{{ route('barang.edit', $b) }}" class="btn btn-outline" style="padding:4px 10px;font-size:12px;">Edit</a>
                            <form method="POST" action="{{ route('barang.destroy', $b) }}" onsubmit="return confirm('Hapus barang {{ $b->nama_barang }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding:4px 10px;font-size:12px;color:var(--danger-text);border-color:var(--danger-bg);">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:40px;text-align:center;color:var(--text-muted);font-size:13px;">
                        Tidak ada barang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barang->hasPages())
    <div style="padding:14px 18px;border-top:var(--border);display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:12px;color:var(--text-muted);">
            Menampilkan {{ $barang->firstItem() }}–{{ $barang->lastItem() }} dari {{ $barang->total() }} barang
        </div>
        <div style="display:flex;gap:4px;">
            {{ $barang->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

@endsection