<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota {{ $transaksi->kode_transaksi }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Courier New', monospace; font-size: 12px; color: #000; background: #fff; width: 300px; margin: 0 auto; padding: 12px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; margin: 3px 0; }
        .item-nama { font-weight: bold; margin-top: 6px; }
        .item-detail { display: flex; justify-content: space-between; color: #333; }
        .total-row { display: flex; justify-content: space-between; font-weight: bold; font-size: 13px; margin: 4px 0; }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="center bold" style="font-size:14px;">TOKO RATIH</div>
<div class="center" style="font-size:11px;">Sistem Manajemen Grosir</div>
<div class="divider"></div>

<div class="row"><span>No.</span><span>{{ $transaksi->kode_transaksi }}</span></div>
<div class="row"><span>Tanggal</span><span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span></div>
<div class="row"><span>Kasir</span><span>{{ $transaksi->user->name ?? '—' }}</span></div>
@if($transaksi->pelanggan)
<div class="row"><span>Pelanggan</span><span>{{ $transaksi->pelanggan }}</span></div>
@endif

<div class="divider"></div>

@foreach($transaksi->detailTransaksi as $detail)
<div class="item-nama">{{ $detail->barang->nama_barang ?? '—' }}</div>
<div class="item-detail">
    <span>{{ $detail->qty }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}{{ $detail->diskon > 0 ? ' (-'.$detail->diskon.'%)' : '' }}</span>
    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
</div>
@endforeach

<div class="divider"></div>

<div class="total-row"><span>TOTAL</span><span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></div>
<div class="row"><span>Bayar</span><span>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></div>
<div class="row"><span>Kembali</span><span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span></div>

<div class="divider"></div>

<div class="center" style="margin-top:8px;">
    @if($transaksi->status === 'lunas')
        *** LUNAS ***
    @elseif($transaksi->status === 'piutang')
        *** PIUTANG — BELUM LUNAS ***
    @endif
</div>
<div class="center" style="margin-top:6px;font-size:11px;">Terima kasih sudah belanja!</div>

@if($transaksi->catatan)
<div class="divider"></div>
<div style="font-size:11px;color:#555;">Catatan: {{ $transaksi->catatan }}</div>
@endif

<div class="divider" style="margin-top:12px;"></div>

<div class="center no-print" style="margin-top:14px;">
    <button onclick="window.print()"
        style="padding:8px 20px;background:#185FA5;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">
        Cetak Nota
    </button>
    <button onclick="window.close()"
        style="padding:8px 20px;background:#F0F0EC;color:#444;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px;">
        Tutup
    </button>
</div>

<script>
    // Auto print saat halaman dibuka
    // window.onload = () => window.print();
</script>
</body>
</html>