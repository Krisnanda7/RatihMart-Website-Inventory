@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Laporan Laba Rugi</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">
            {{ \Carbon\Carbon::parse($dari)->format('d M') }} — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
        </p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <form method="GET" action="{{ route('laporan.laba-rugi') }}" style="display:flex;gap:8px;align-items:center;">
            <input type="month" name="bulan" value="{{ $bulan }}"
                style="padding:7px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            <button type="submit" class="btn btn-primary" style="padding:7px 16px;">Tampilkan</button>
        </form>
        <button onclick="window.print()" class="btn btn-outline">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="2" y="5" width="12" height="8" rx="1" stroke="currentColor" stroke-width="1.3"/><path d="M5 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2M5 11h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Cetak
        </button>
    </div>
</div>

{{-- RINGKASAN LABA RUGI --}}
<div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:16px;">
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Total Pendapatan</div>
            <div style="font-size:18px;font-weight:600;color:var(--brand);">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">Dari transaksi lunas</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">HPP (Harga Pokok)</div>
            <div style="font-size:18px;font-weight:600;color:var(--danger-text);">Rp {{ number_format($hpp, 0, ',', '.') }}</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">Total harga beli barang terjual</div>
        </div>
    </div>
    <div class="card" style="{{ $labaKotor >= 0 ? 'border-color:#C0DD97;' : 'border-color:#F7C1C1;' }}">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">Laba Kotor</div>
            <div style="font-size:18px;font-weight:600;color:{{ $labaKotor >= 0 ? 'var(--success-text)' : 'var(--danger-text)' }};">
                Rp {{ number_format($labaKotor, 0, ',', '.') }}
            </div>
            <div style="font-size:11px;color:{{ $labaKotor >= 0 ? 'var(--success-text)' : 'var(--danger-text)' }};margin-top:3px;">
                Margin {{ $marginPersen }}%
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;">vs Bulan Lalu</div>
            <div style="font-size:18px;font-weight:600;color:{{ $persenLabaChange >= 0 ? 'var(--success-text)' : 'var(--danger-text)' }};">
                {{ $persenLabaChange >= 0 ? '+' : '' }}{{ $persenLabaChange }}%
            </div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">Laba bulan lalu: Rp {{ number_format($labaBulanLalu, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- BAGAN LABA RUGI RINGKAS --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-header"><span class="card-title">Ringkasan Laba Rugi</span></div>
    <div class="card-body">
        <div style="display:flex;gap:0;align-items:stretch;border:var(--border);border-radius:var(--radius-md);overflow:hidden;">
            <div style="flex:1;padding:16px 20px;border-right:var(--border);">
                <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:12px;">Pendapatan</div>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #F5F5F2;font-size:13px;">
                    <span style="color:var(--text-secondary);">Penjualan Bersih</span>
                    <span style="font-weight:500;">Rp {{ number_format($pendapatan, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:14px;font-weight:600;">
                    <span style="color:var(--text-primary);">Total Pendapatan</span>
                    <span style="color:var(--brand);">Rp {{ number_format($pendapatan, 0, ',', '.') }}</span>
                </div>
            </div>
            <div style="flex:1;padding:16px 20px;border-right:var(--border);">
                <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:12px;">Beban Pokok</div>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #F5F5F2;font-size:13px;">
                    <span style="color:var(--text-secondary);">Harga Pokok Penjualan</span>
                    <span style="font-weight:500;color:var(--danger-text);">Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:14px;font-weight:600;">
                    <span style="color:var(--text-primary);">Total Beban</span>
                    <span style="color:var(--danger-text);">Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                </div>
            </div>
            <div style="flex:1;padding:16px 20px;background:{{ $labaKotor >= 0 ? 'var(--success-bg)' : 'var(--danger-bg)' }};">
                <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:12px;">Laba Bersih</div>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid {{ $labaKotor >= 0 ? '#C0DD97' : '#F7C1C1' }};font-size:13px;">
                    <span style="color:var(--text-secondary);">Pendapatan − HPP</span>
                    <span style="font-weight:500;">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:14px;font-weight:600;">
                    <span>Margin Laba</span>
                    <span style="color:{{ $labaKotor >= 0 ? 'var(--success-text)' : 'var(--danger-text)' }};font-size:20px;">{{ $marginPersen }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TREND HARIAN + LABA PER KATEGORI --}}
<div style="display:grid;grid-template-columns:1.4fr 0.6fr;gap:12px;margin-bottom:12px;">

    {{-- TREND LABA HARIAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Trend Laba Harian</span>
            <span style="display:flex;gap:12px;font-size:11px;">
                <span style="display:flex;align-items:center;gap:4px;color:var(--text-muted);">
                    <span style="display:inline-block;width:8px;height:8px;background:var(--brand);border-radius:2px;"></span>Pendapatan
                </span>
                <span style="display:flex;align-items:center;gap:4px;color:var(--text-muted);">
                    <span style="display:inline-block;width:8px;height:8px;background:#1D9E75;border-radius:2px;"></span>Laba
                </span>
            </span>
        </div>
        <div class="card-body">
            @if($trendHarian->count() > 0)
            @php $maxTrend = max($trendHarian->max('pendapatan'), 1); @endphp
            <div style="display:flex;align-items:flex-end;gap:4px;height:120px;padding-bottom:20px;">
                @foreach($trendHarian as $t)
                @php
                    $tinggiP = max(2, round(($t->pendapatan / $maxTrend) * 110));
                    $tinggiL = max(2, round(($t->laba / $maxTrend) * 110));
                @endphp
                <div style="flex:1;display:flex;align-items:flex-end;gap:1px;position:relative;"
                    title="{{ \Carbon\Carbon::parse($t->tanggal)->format('d M') }}&#10;Pendapatan: Rp {{ number_format($t->pendapatan,0,',','.') }}&#10;Laba: Rp {{ number_format($t->laba,0,',','.') }}">
                    <div style="flex:1;height:{{ $tinggiP }}px;background:var(--brand);border-radius:2px 2px 0 0;opacity:.7;"></div>
                    <div style="flex:1;height:{{ $tinggiL }}px;background:#1D9E75;border-radius:2px 2px 0 0;opacity:.9;"></div>
                    <div style="position:absolute;bottom:-18px;left:50%;transform:translateX(-50%);font-size:9px;color:var(--text-muted);white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d') }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="height:120px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:13px;">
                Belum ada data pada periode ini
            </div>
            @endif
        </div>
    </div>

    {{-- LABA PER KATEGORI --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Laba per Kategori</span></div>
        <div class="card-body">
            @php
                $maxLaba = $labaPerKategori->max('laba') ?: 1;
                $warna   = ['#185FA5','#1D9E75','#EF9F27','#D4537E','#7F77DD','#888780'];
                $li = 0;
            @endphp
            @forelse($labaPerKategori as $kat)
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:var(--text-secondary);">{{ $kat->kategori ?? 'Lainnya' }}</span>
                    <span style="font-weight:500;color:var(--success-text);">Rp {{ number_format($kat->laba/1000, 0, ',', '.') }}K</span>
                </div>
                <div style="height:5px;background:#F0F0EC;border-radius:3px;">
                    <div style="height:5px;background:{{ $warna[$li % count($warna)] }};border-radius:3px;width:{{ max(2, round(($kat->laba / $maxLaba) * 100)) }}%;"></div>
                </div>
            </div>
            @php $li++; @endphp
            @empty
            <div style="color:var(--text-muted);font-size:13px;">Belum ada data</div>
            @endforelse
        </div>
    </div>

</div>

{{-- LABA PER BARANG --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Laba per Barang</span>
        <span style="font-size:11px;color:var(--text-muted);">Top 10 tertinggi</span>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="border-bottom:var(--border);">
                    <th style="padding:10px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">#</th>
                    <th style="padding:10px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Barang</th>
                    <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Qty Terjual</th>
                    <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Pendapatan</th>
                    <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">HPP</th>
                    <th style="padding:10px 18px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Laba</th>
                    <th style="padding:10px 18px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Margin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labaPerBarang as $i => $b)
                @php $margin = $b->pendapatan > 0 ? round(($b->laba / $b->pendapatan) * 100, 1) : 0; @endphp
                <tr style="border-bottom:1px solid #F5F5F2;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background=''">
                    <td style="padding:11px 18px;color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
                    <td style="padding:11px 18px;">
                        <div style="font-weight:500;color:var(--text-primary);">{{ $b->nama_barang }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $b->kategori ?? '—' }}</div>
                    </td>
                    <td style="padding:11px 12px;text-align:center;color:var(--text-secondary);">{{ number_format($b->qty_terjual) }}</td>
                    <td style="padding:11px 12px;text-align:right;color:var(--text-primary);">Rp {{ number_format($b->pendapatan, 0, ',', '.') }}</td>
                    <td style="padding:11px 12px;text-align:right;color:var(--danger-text);">Rp {{ number_format($b->hpp, 0, ',', '.') }}</td>
                    <td style="padding:11px 18px;text-align:right;font-weight:600;color:var(--success-text);">Rp {{ number_format($b->laba, 0, ',', '.') }}</td>
                    <td style="padding:11px 18px;text-align:right;">
                        <span style="background:{{ $margin >= 20 ? 'var(--success-bg)' : ($margin >= 10 ? 'var(--warning-bg)' : 'var(--danger-bg)') }};color:{{ $margin >= 20 ? 'var(--success-text)' : ($margin >= 10 ? 'var(--warning-text)' : 'var(--danger-text)') }};padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;">
                            {{ $margin }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding:30px;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada data penjualan pada periode ini</td></tr>
                @endforelse
            </tbody>
            @if($labaPerBarang->count() > 0)
            <tfoot>
                <tr style="background:#FAFAF8;border-top:var(--border);">
                    <td colspan="3" style="padding:11px 18px;font-weight:600;color:var(--text-primary);">Total</td>
                    <td style="padding:11px 12px;text-align:right;font-weight:600;color:var(--brand);">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
                    <td style="padding:11px 12px;text-align:right;font-weight:600;color:var(--danger-text);">Rp {{ number_format($hpp, 0, ',', '.') }}</td>
                    <td style="padding:11px 18px;text-align:right;font-weight:600;color:var(--success-text);">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                    <td style="padding:11px 18px;text-align:right;font-weight:600;">{{ $marginPersen }}%</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection