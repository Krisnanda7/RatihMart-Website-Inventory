@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Selamat datang, {{ Auth::user()->name ?? 'Ratih' }}</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Ringkasan aktivitas toko grosiran hari ini</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 3v10M3 8h10" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
        Buat Transaksi
    </a>
</div>

@if($stockAlertCount > 0)
<div class="alert alert-warning">
    <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M8 2L14 13H2L8 2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M8 7v3M8 11.5v.3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
    <strong>{{ $stockAlertCount }} barang</strong>&nbsp;mendekati batas stok minimum — segera lakukan pemesanan ulang ke supplier.
    <a href="{{ route('barang.index') }}?filter=low_stock" style="margin-left:auto;font-weight:600;color:var(--warning-text);text-decoration:underline;white-space:nowrap;">Lihat barang →</a>
</div>
@endif

<div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:20px;">
    <div class="card">
        <div class="card-body">
            <div style="width:34px;height:34px;background:var(--info-bg);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><rect x="1" y="5" width="14" height="9" rx="1.5" stroke="#185FA5" stroke-width="1.3"/><path d="M5 5V3.5A2.5 2.5 0 0 1 8 1v0a2.5 2.5 0 0 1 3 2.5V5" stroke="#185FA5" stroke-width="1.3"/></svg>
            </div>
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Total SKU Aktif</div>
            <div style="font-size:22px;font-weight:600;color:var(--text-primary);">{{ number_format($totalBarang) }}</div>
            <div style="font-size:11px;color:var(--success-text);margin-top:4px;">{{ $barangBaruBulanIni }} baru bulan ini</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="width:34px;height:34px;background:var(--success-bg);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2 12L5.5 7.5l3 3L11 5l3 4.5" stroke="#3B6D11" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Transaksi Hari Ini</div>
            <div style="font-size:22px;font-weight:600;color:var(--text-primary);">{{ number_format($transaksiHariIni) }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">
                @if($selisihTransaksi >= 0)
                    <span style="color:var(--success-text);">+{{ $selisihTransaksi }}</span>
                @else
                    <span style="color:var(--danger-text);">{{ $selisihTransaksi }}</span>
                @endif
                dari kemarin
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="width:34px;height:34px;background:var(--warning-bg);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="#854F0B" stroke-width="1.3"/><path d="M8 5v2.5l2 1.5" stroke="#854F0B" stroke-width="1.3" stroke-linecap="round"/></svg>
            </div>
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Pendapatan Hari Ini</div>
            <div style="font-size:18px;font-weight:600;color:var(--text-primary);">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div style="font-size:11px;margin-top:4px;">
                @if($persenPendapatan >= 0)
                    <span style="color:var(--success-text);">+{{ $persenPendapatan }}%</span>
                @else
                    <span style="color:var(--danger-text);">{{ $persenPendapatan }}%</span>
                @endif
                dari kemarin
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="width:34px;height:34px;background:var(--danger-bg);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 12V5l5-3 5 3v7l-5 3-5-3z" stroke="#A32D2D" stroke-width="1.3"/></svg>
            </div>
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Stok Hampir Habis</div>
            <div style="font-size:22px;font-weight:600;color:{{ $stockAlertCount > 0 ? 'var(--danger-text)' : 'var(--text-primary)' }};">{{ $stockAlertCount }}</div>
            <div style="font-size:11px;color:var(--danger-text);margin-top:4px;">{{ $stockAlertCount > 0 ? 'Perlu restock segera' : 'Semua aman' }}</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:12px;margin-bottom:12px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Transaksi Terbaru</span>
            <a href="{{ route('transaksi.index') }}" class="card-action">Lihat semua →</a>
        </div>
        <div style="padding:0 18px;">
            <div style="display:grid;grid-template-columns:1fr auto auto;gap:8px;padding:8px 0;font-size:11px;color:var(--text-muted);border-bottom:1px solid #F0F0EC;font-weight:600;text-transform:uppercase;letter-spacing:.03em;">
                <span>Pelanggan / ID</span>
                <span style="text-align:right;padding-right:10px;">Total</span>
                <span>Status</span>
            </div>
            @forelse($transaksiTerbaru as $trx)
            <div style="display:grid;grid-template-columns:1fr auto auto;gap:8px;align-items:center;padding:10px 0;border-bottom:1px solid #F5F5F2;">
                <div>
                    <div style="font-size:13px;font-weight:500;color:var(--text-primary);">{{ $trx->pelanggan ?? 'Umum' }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">#{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }} · {{ $trx->created_at->format('H:i') }}</div>
                </div>
                <div style="font-size:13px;font-weight:500;color:var(--text-primary);text-align:right;padding-right:10px;">
                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                </div>
                <div>
                    @if($trx->status === 'lunas')
                        <span class="badge badge-success">Lunas</span>
                    @elseif($trx->status === 'piutang')
                        <span class="badge badge-warning">Piutang</span>
                    @else
                        <span class="badge badge-info">Proses</span>
                    @endif
                </div>
            </div>
            @empty
            <div style="padding:24px 0;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada transaksi hari ini</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Penjualan per Kategori</span>
            <span style="font-size:11px;color:var(--text-muted);">Bulan ini</span>
        </div>
        <div class="card-body">
            @php
                $colors = ['#378ADD','#1D9E75','#EF9F27','#D4537E','#7F77DD','#888780'];
                $maxVal = $kategoriPenjualan->max('total') ?: 1;
                $i = 0;
            @endphp
            @forelse($kategoriPenjualan as $kat)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:10px;height:10px;border-radius:2px;background:{{ $colors[$i % count($colors)] }};flex-shrink:0;"></div>
                <div style="font-size:12px;color:var(--text-secondary);width:90px;flex-shrink:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $kat->kategori ?? 'Lainnya' }}</div>
                <div style="flex:1;height:5px;background:#F0F0EC;border-radius:3px;">
                    <div style="height:5px;border-radius:3px;background:{{ $colors[$i % count($colors)] }};width:{{ round(($kat->total / $maxVal) * 100) }}%;"></div>
                </div>
                <div style="font-size:11px;color:var(--text-secondary);width:62px;text-align:right;flex-shrink:0;">
                    Rp {{ number_format($kat->total / 1000, 0, ',', '.') }}K
                </div>
            </div>
            @php $i++; @endphp
            @empty
            <div style="padding:20px 0;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada data</div>
            @endforelse
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:12px;">
    <div class="card-header">
        <span class="card-title">Pergerakan Stok Minggu Ini</span>
        <span style="font-size:12px;color:var(--text-muted);">Masuk / Keluar</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 220px;gap:18px;align-items:start;">
            <div>
                <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:10px;align-items:end;min-height:140px;">
                    @foreach($stockMovement as $row)
                        <div style="display:flex;flex-direction:column;gap:4px;align-items:center;">
                            <div style="width:100%;background:#F0F0EC;border-radius:10px;overflow:hidden;position:relative;height:100px;">
                                <div style="width:100%;height:{{ $row['total_in'] / max($stockMovement->max('total_in'), 1) * 100 }}%;background:#1D9E75;position:absolute;bottom:0;"></div>
                                <div style="width:100%;height:{{ $row['total_out'] / max($stockMovement->max('total_out'), 1) * 100 }}%;background:#A32D2D;position:absolute;bottom:0;opacity:.75;"></div>
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $row['tanggal'] }}</div>
                        </div>
                    @endforeach
                </div>
                <div style="display:flex;gap:12px;margin-top:12px;font-size:12px;">
                    <div style="display:flex;align-items:center;gap:6px;color:var(--success-text);">
                        <span style="width:10px;height:10px;border-radius:50%;background:#1D9E75;display:inline-block;"></span>
                        Masuk
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;color:var(--danger-text);">
                        <span style="width:10px;height:10px;border-radius:50%;background:#A32D2D;display:inline-block;"></span>
                        Keluar
                    </div>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="background:var(--bg-page);border-radius:var(--radius-md);padding:14px;">
                    <div style="font-size:11px;color:var(--text-secondary);margin-bottom:8px;">Total masuk</div>
                    <div style="font-size:22px;font-weight:600;color:var(--success-text);">{{ number_format($stockMovement->sum('total_in')) }}</div>
                </div>
                <div style="background:var(--bg-page);border-radius:var(--radius-md);padding:14px;">
                    <div style="font-size:11px;color:var(--text-secondary);margin-bottom:8px;">Total keluar</div>
                    <div style="font-size:22px;font-weight:600;color:var(--danger-text);">{{ number_format($stockMovement->sum('total_out')) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:12px;">
    <div class="card-header">
        <span class="card-title">Top Stok Tersedia</span>
        <a href="{{ route('barang.index') }}" class="card-action">Kelola barang →</a>
    </div>
    <div class="card-body">
        @if($currentStockTop->isEmpty())
            <div style="padding:24px 0;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada data stok</div>
        @else
            <div style="display:grid;gap:12px;">
                @foreach($currentStockTop as $barang)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:#F8F9F6;border-radius:var(--radius-md);">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">{{ $barang->nama_barang }}</div>
                            <div style="font-size:11px;color:var(--text-secondary);">{{ $barang->kategori ?? 'Tanpa kategori' }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:16px;font-weight:600;color:var(--brand);">{{ $barang->stok }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $barang->satuan }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@if($barangStokRendah->count() > 0)
<div class="card">
    <div class="card-header">
        <span class="card-title">Barang Perlu Restock</span>
        <a href="{{ route('barang.index') }}" class="card-action">Kelola stok →</a>
    </div>
    <div style="padding:12px 18px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:8px;">
            @foreach($barangStokRendah as $barang)
            <div style="background:#F9F9F7;border:1px solid #EEEEED;border-radius:var(--radius-md);padding:12px 14px;">
                <div style="font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:4px;line-height:1.3;">{{ $barang->nama_barang }}</div>
                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:8px;">
                    Stok: <strong>{{ $barang->stok }} {{ $barang->satuan }}</strong>
                    · Min: {{ $barang->stok_minimum }} {{ $barang->satuan }}
                </div>
                @if($barang->stok <= ($barang->stok_minimum / 2))
                    <span class="badge badge-danger">Kritis</span>
                @else
                    <span class="badge badge-warning">Rendah</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection