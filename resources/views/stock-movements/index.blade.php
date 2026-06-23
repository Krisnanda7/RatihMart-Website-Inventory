@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Manajemen Stok</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Lihat riwayat stok masuk dan keluar, atau tambahkan penyesuaian.</p>
    </div>
    <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">Tambah Stok</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('stock-movements.index') }}" style="display:grid;grid-template-columns:1fr 1fr 1fr 120px;gap:12px;align-items:end;">
            <div>
                <label class="sr-only">Barang</label>
                <input type="text" name="barang" value="{{ request('barang') }}" placeholder="Cari barang" style="width:100%;padding:10px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;" />
            </div>
            <div>
                <label class="sr-only">Jenis</label>
                <select name="direction" style="width:100%;padding:10px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;">
                    <option value="">Semua jenis</option>
                    <option value="in" {{ request('direction') === 'in' ? 'selected' : '' }}>Masuk</option>
                    <option value="out" {{ request('direction') === 'out' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <input type="date" name="dari" value="{{ request('dari') }}" style="padding:10px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;" />
                <input type="date" name="sampai" value="{{ request('sampai') }}" style="padding:10px 12px;border:1px solid #D2D2CC;border-radius:var(--radius-md);font-size:13px;" />
            </div>
            <button type="submit" class="btn btn-outline" style="width:100%;">Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Riwayat Penyesuaian Stok</span>
    </div>
    <div class="card-body">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="text-align:left;color:var(--text-secondary);font-size:11px;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #F0F0EC;">
                        <th style="padding:12px 10px;">Tanggal</th>
                        <th style="padding:12px 10px;">Barang</th>
                        <th style="padding:12px 10px;">Jenis</th>
                        <th style="padding:12px 10px;">Jumlah</th>
                        <th style="padding:12px 10px;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockMovements as $movement)
                        <tr style="border-bottom:1px solid #F5F5F2;">
                            <td style="padding:12px 10px;color:var(--text-primary);">{{ $movement->created_at->format('d M Y H:i') }}</td>
                            <td style="padding:12px 10px;">{{ $movement->barang->nama_barang ?? '—' }}</td>
                            <td style="padding:12px 10px;">
                                @if($movement->direction === 'in')
                                    <span class="badge badge-success">Masuk</span>
                                @else
                                    <span class="badge badge-danger">Keluar</span>
                                @endif
                            </td>
                            <td style="padding:12px 10px;">{{ $movement->qty }}</td>
                            <td style="padding:12px 10px;color:var(--text-secondary);">{{ $movement->notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:20px 10px;text-align:center;color:var(--text-muted);">Belum ada penyesuaian stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">{{ $stockMovements->links() }}</div>
    </div>
</div>

@endsection
