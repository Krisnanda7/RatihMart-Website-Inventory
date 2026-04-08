@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Laporan Penjualan</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">
            {{ \Carbon\Carbon::parse($dari)->format('d M Y') }}
            @if($dari !== $sampai) — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }} @endif
        </p>
    </div>
    <a href="{{ route('laporan.penjualan') }}?{{ http_build_query(array_merge(request()->all(), ['export' => 'print'])) }}"
        class="btn btn-outline" target="_blank" onclick="window.print();return false;">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="2" y="5" width="12" height="8" rx="1" stroke="currentColor" stroke-width="1.3"/><path d="M5 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2M5 11h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Cetak
    </a>
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:12px 18px;">
        <form method="GET" action="{{ route('laporan.penjualan') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <select name="periode" id="periode-select" onchange="togglePeriode(this.value)"
                style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);background:var(--bg-white);outline:none;">
                <option value="harian"  {{ $periode === 'harian'  ? 'selected' : '' }}>Harian</option>
                <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>

            {{-- Harian: pilih rentang tanggal --}}
            <div id="filter-harian" style="{{ $periode === 'harian' ? '' : 'display:none;' }}display:flex;gap:8px;align-items:center;">
                <input type="date" name="dari" value="{{ $dari }}"
                    style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                <span style="font-size:12px;color:var(--text-muted);">s/d</span>
                <input type="date" name="sampai" value="{{ $sampai }}"
                    style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            </div>

            {{-- Bulanan: pilih bulan --}}
            <div id="filter-bulanan" style="{{ $periode === 'bulanan' ? '' : 'display:none;' }}">
                <input type="month" name="bulan" value="{{ request('bulan', \Carbon\Carbon::now()->format('Y-m')) }}"
                    style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            </div>

            <button type="submit" class="btn btn-primary" style="padding:7px 16px;">Tampilkan</button>
        </form>
    </div>
</div>

{{-- RINGKASAN --}}
<div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:16px;">
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Total Transaksi</div>
            <div style="font-size:24px;font-weight:600;color:var(--text-primary);">{{ number_format($ringkasan->total_transaksi) }}</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Total Pendapatan</div>
            <div style="font-size:18px;font-weight:600;color:var(--brand);">Rp {{ number_format($ringkasan->total_pendapatan, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--success-text);margin-top:3px;">Dari transaksi lunas</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Total Piutang</div>
            <div style="font-size:18px;font-weight:600;color:{{ $ringkasan->total_piutang > 0 ? 'var(--warning-text)' : 'var(--text-primary)' }};">
                Rp {{ number_format($ringkasan->total_piutang, 0, ',', '.') }}
            </div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">Belum lunas</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Transaksi Batal</div>
            <div style="font-size:24px;font-weight:600;color:{{ $ringkasan->total_batal > 0 ? 'var(--danger-text)' : 'var(--text-primary)' }};">
                {{ $ringkasan->total_batal }}
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK + KATEGORI --}}
<div style="display:grid;grid-template-columns:1.4fr 0.6fr;gap:12px;margin-bottom:12px;">

    {{-- GRAFIK HARIAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Grafik Penjualan Harian</span>
            <span style="font-size:11px;color:var(--text-muted);">Transaksi lunas</span>
        </div>
        <div class="card-body">
            @if($grafikHarian->count() > 0)
            @php
                $maxGrafik = $grafikHarian->max('total') ?: 1;
            @endphp
            <div style="display:flex;align-items:flex-end;gap:6px;height:140px;padding-bottom:24px;position:relative;">
                @foreach($grafikHarian as $g)
                @php $tinggi = max(4, round(($g->total / $maxGrafik) * 120)); @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;position:relative;"
                    title="{{ \Carbon\Carbon::parse($g->tanggal)->format('d M') }}: Rp {{ number_format($g->total, 0, ',', '.') }} ({{ $g->jumlah }} trx)">
                    <div style="width:100%;height:{{ $tinggi }}px;background:var(--brand);border-radius:3px 3px 0 0;opacity:.85;transition:opacity .15s;cursor:pointer;"
                        onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.85"></div>
                    <div style="position:absolute;bottom:-20px;font-size:9px;color:var(--text-muted);white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($g->tanggal)->format('d/m') }}
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:8px;font-size:11px;color:var(--text-muted);text-align:right;">
                Hover batang untuk detail
            </div>
            @else
            <div style="height:140px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:13px;">
                Belum ada data penjualan pada periode ini
            </div>
            @endif
        </div>
    </div>

    {{-- PER KATEGORI --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Per Kategori</span>
        </div>
        <div class="card-body">
            @php
                $maxKat = $perKategori->max('total') ?: 1;
                $warna  = ['#378ADD','#1D9E75','#EF9F27','#D4537E','#7F77DD','#888780'];
                $ki = 0;
            @endphp
            @forelse($perKategori as $kat)
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <div style="width:8px;height:8px;border-radius:2px;background:{{ $warna[$ki % count($warna)] }};flex-shrink:0;"></div>
                <div style="font-size:12px;color:var(--text-secondary);flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $kat->kategori ?? 'Lainnya' }}</div>
                <div style="font-size:11px;color:var(--text-secondary);flex-shrink:0;">Rp {{ number_format($kat->total/1000, 0, ',', '.') }}K</div>
            </div>
            @php $ki++; @endphp
            @empty
            <div style="color:var(--text-muted);font-size:13px;">Belum ada data</div>
            @endforelse
        </div>
    </div>

</div>

{{-- BARANG TERLARIS + TRANSAKSI --}}
<div style="display:grid;grid-template-columns:0.6fr 1.4fr;gap:12px;margin-bottom:12px;">

    {{-- BARANG TERLARIS --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Barang Terlaris</span>
            <span style="font-size:11px;color:var(--text-muted);">Top 10</span>
        </div>
        <div style="padding:0 18px;">
            @forelse($barangTerlaris as $i => $b)
            <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #F5F5F2;">
                <div style="width:20px;height:20px;border-radius:50%;background:{{ $i < 3 ? 'var(--brand)' : 'var(--bg-page)' }};display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;color:{{ $i < 3 ? '#fff' : 'var(--text-muted)' }};flex-shrink:0;">{{ $i+1 }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:12px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $b->nama_barang }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ number_format($b->total_qty) }} {{ $b->satuan }}</div>
                </div>
                <div style="font-size:11px;color:var(--text-secondary);flex-shrink:0;text-align:right;">
                    Rp {{ number_format($b->total_nilai/1000, 0, ',', '.') }}K
                </div>
            </div>
            @empty
            <div style="padding:24px 0;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada data</div>
            @endforelse
        </div>
    </div>

    {{-- DAFTAR TRANSAKSI --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Daftar Transaksi</span>
            <a href="{{ route('transaksi.index') }}" class="card-action">Lihat semua →</a>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:12px;">
                <thead>
                    <tr style="border-bottom:var(--border);">
                        <th style="padding:9px 18px;text-align:left;font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Kode / Waktu</th>
                        <th style="padding:9px 12px;text-align:left;font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Pelanggan</th>
                        <th style="padding:9px 12px;text-align:right;font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Total</th>
                        <th style="padding:9px 12px;text-align:center;font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $trx)
                    <tr style="border-bottom:1px solid #F5F5F2;">
                        <td style="padding:9px 18px;">
                            <div style="font-weight:500;color:var(--text-primary);">{{ $trx->kode_transaksi }}</div>
                            <div style="font-size:10px;color:var(--text-muted);">{{ $trx->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td style="padding:9px 12px;color:var(--text-secondary);">{{ $trx->pelanggan ?? 'Umum' }}</td>
                        <td style="padding:9px 12px;text-align:right;font-weight:500;color:var(--text-primary);">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td style="padding:9px 12px;text-align:center;">
                            @if($trx->status === 'lunas')
                                <span class="badge badge-success">Lunas</span>
                            @elseif($trx->status === 'piutang')
                                <span class="badge badge-warning">Piutang</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="padding:24px;text-align:center;color:var(--text-muted);">Tidak ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksi->hasPages())
        <div style="padding:12px 18px;border-top:var(--border);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:11px;color:var(--text-muted);">{{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }}</span>
            {{ $transaksi->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
function togglePeriode(val) {
    document.getElementById('filter-harian').style.display  = val === 'harian'  ? 'flex' : 'none';
    document.getElementById('filter-bulanan').style.display = val === 'bulanan' ? 'block' : 'none';
}
</script>
@endpush