@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Daftar Transaksi</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Total {{ $transaksi->total() }} transaksi</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
        Buat Transaksi
    </a>
</div>

{{-- RINGKASAN HARI INI --}}
<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:16px;">
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Transaksi Hari Ini</div>
            <div style="font-size:22px;font-weight:600;color:var(--text-primary);">{{ $totalHariIni }}</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Pendapatan Hari Ini</div>
            <div style="font-size:18px;font-weight:600;color:var(--text-primary);">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Total Piutang Belum Lunas</div>
            <div style="font-size:18px;font-weight:600;color:{{ $piutangBelumLunas > 0 ? 'var(--warning-text)' : 'var(--text-primary)' }};">Rp {{ number_format($piutangBelumLunas, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:12px 18px;">
        <form method="GET" action="{{ route('transaksi.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan atau kode..."
                style="flex:1;min-width:180px;padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            <input type="date" name="dari" value="{{ request('dari') }}"
                style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            <select name="status"
                style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);background:var(--bg-white);outline:none;">
                <option value="">Semua Status</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="piutang" {{ request('status') == 'piutang' ? 'selected' : '' }}>Piutang</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding:7px 16px;">Cari</button>
            @if(request()->hasAny(['search','dari','sampai','status']))
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline" style="padding:7px 16px;">Reset</a>
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
                    <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Pelanggan</th>
                    <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Tanggal</th>
                    <th style="padding:11px 18px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Total</th>
                    <th style="padding:11px 18px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Status</th>
                    <th style="padding:11px 18px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $trx)
                <tr style="border-bottom:1px solid #F5F5F2;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background=''">
                    <td style="padding:12px 18px;font-size:12px;font-weight:500;color:var(--text-muted);">{{ $trx->kode_transaksi }}</td>
                    <td style="padding:12px 18px;">
                        <div style="font-weight:500;color:var(--text-primary);">{{ $trx->pelanggan ?? 'Umum' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">Kasir: {{ $trx->user->name ?? '—' }}</div>
                    </td>
                    <td style="padding:12px 18px;color:var(--text-secondary);">
                        <div>{{ $trx->created_at->format('d M Y') }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $trx->created_at->format('H:i') }}</div>
                    </td>
                    <td style="padding:12px 18px;text-align:right;font-weight:600;color:var(--text-primary);">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td style="padding:12px 18px;text-align:center;">
                        @if($trx->status === 'lunas')
                            <span class="badge badge-success">Lunas</span>
                        @elseif($trx->status === 'piutang')
                            <span class="badge badge-warning">Piutang</span>
                        @else
                            <span class="badge badge-danger">Batal</span>
                        @endif
                    </td>
                    <td style="padding:12px 18px;text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('transaksi.show', $trx) }}" class="btn btn-outline" style="padding:4px 10px;font-size:12px;">Detail</a>
                            <a href="{{ route('transaksi.nota', $trx) }}" class="btn btn-outline" style="padding:4px 10px;font-size:12px;" target="_blank">Nota</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:40px;text-align:center;color:var(--text-muted);font-size:13px;">Tidak ada transaksi ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksi->hasPages())
    <div style="padding:14px 18px;border-top:var(--border);display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:12px;color:var(--text-muted);">
            Menampilkan {{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} transaksi
        </div>
        {{ $transaksi->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection