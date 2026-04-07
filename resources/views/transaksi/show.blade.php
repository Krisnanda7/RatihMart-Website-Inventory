@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('transaksi.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;display:inline-block;margin-bottom:10px;">← Kembali ke Daftar Transaksi</a>
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">{{ $transaksi->kode_transaksi }}</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">{{ $transaksi->created_at->format('d M Y, H:i') }} · Kasir: {{ $transaksi->user->name ?? '—' }}</p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            @if($transaksi->status === 'lunas')
                <span class="badge badge-success" style="padding:5px 12px;font-size:12px;">Lunas</span>
            @elseif($transaksi->status === 'piutang')
                <span class="badge badge-warning" style="padding:5px 12px;font-size:12px;">Piutang</span>
            @else
                <span class="badge badge-danger" style="padding:5px 12px;font-size:12px;">Batal</span>
            @endif
            <a href="{{ route('transaksi.nota', $transaksi) }}" class="btn btn-outline" target="_blank">Cetak Nota</a>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:14px;align-items:start;">

    {{-- DETAIL BARANG --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Barang yang Dibeli</span></div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="border-bottom:var(--border);">
                        <th style="padding:10px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Barang</th>
                        <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Qty</th>
                        <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Harga</th>
                        <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Diskon</th>
                        <th style="padding:10px 18px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detailTransaksi as $detail)
                    <tr style="border-bottom:1px solid #F5F5F2;">
                        <td style="padding:12px 18px;">
                            <div style="font-weight:500;color:var(--text-primary);">{{ $detail->barang->nama_barang ?? '—' }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $detail->barang->kode_barang ?? '' }}</div>
                        </td>
                        <td style="padding:12px 12px;text-align:center;color:var(--text-primary);">{{ $detail->qty }} {{ $detail->barang->satuan ?? '' }}</td>
                        <td style="padding:12px 12px;text-align:right;color:var(--text-secondary);">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td style="padding:12px 12px;text-align:center;">
                            @if($detail->diskon > 0)
                                <span class="badge badge-info">{{ $detail->diskon }}%</span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="padding:12px 18px;text-align:right;font-weight:600;color:var(--text-primary);">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-top:var(--border);background:#FAFAF8;">
                        <td colspan="4" style="padding:12px 18px;font-weight:600;color:var(--text-primary);text-align:right;">Total</td>
                        <td style="padding:12px 18px;text-align:right;font-size:15px;font-weight:600;color:var(--brand);">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- INFO TRANSAKSI --}}
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Info Pembayaran</span></div>
            <div class="card-body">
                <table style="width:100%;font-size:13px;border-collapse:collapse;">
                    <tr style="border-bottom:1px solid #F5F5F2;">
                        <td style="padding:8px 0;color:var(--text-muted);">Pelanggan</td>
                        <td style="padding:8px 0;font-weight:500;text-align:right;">{{ $transaksi->pelanggan ?? 'Umum' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #F5F5F2;">
                        <td style="padding:8px 0;color:var(--text-muted);">Total Belanja</td>
                        <td style="padding:8px 0;font-weight:600;text-align:right;color:var(--text-primary);">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #F5F5F2;">
                        <td style="padding:8px 0;color:var(--text-muted);">Uang Bayar</td>
                        <td style="padding:8px 0;font-weight:500;text-align:right;">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:var(--text-muted);">Kembalian</td>
                        <td style="padding:8px 0;font-weight:600;text-align:right;color:var(--success-text);">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
                    </tr>
                </table>
                @if($transaksi->catatan)
                <div style="margin-top:12px;padding:10px 12px;background:var(--bg-page);border-radius:var(--radius-md);font-size:12px;color:var(--text-secondary);">
                    <strong>Catatan:</strong> {{ $transaksi->catatan }}
                </div>
                @endif
            </div>
        </div>

        {{-- UBAH STATUS --}}
        @if($transaksi->status !== 'batal')
        <div class="card">
            <div class="card-header"><span class="card-title">Ubah Status</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('transaksi.status', $transaksi) }}">
                    @csrf @method('PATCH')
                    <select name="status"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);background:var(--bg-white);outline:none;margin-bottom:10px;">
                        <option value="lunas" {{ $transaksi->status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="piutang" {{ $transaksi->status === 'piutang' ? 'selected' : '' }}>Piutang</option>
                        <option value="batal">Batal (stok dikembalikan)</option>
                    </select>
                    <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center;"
                        onclick="return confirm('Yakin ubah status transaksi ini?')">
                        Simpan Status
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection