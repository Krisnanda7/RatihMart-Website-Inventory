@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('transaksi.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;display:inline-block;margin-bottom:10px;">← Kembali ke Daftar Transaksi</a>
    <h1 style="font-size:18px;font-weight:600;color:var(--text-primary);">Buat Transaksi Baru</h1>
    <p style="font-size:13px;color:var(--text-secondary);margin-top:3px;">Isi detail transaksi dan barang yang dibeli</p>
</div>

<form method="POST" action="{{ route('transaksi.store') }}" id="form-transaksi">
@csrf

<div style="display:grid;grid-template-columns:1fr 340px;gap:16px;align-items:start;">

    {{-- KIRI: BARANG --}}
    <div>
        {{-- CARI BARANG --}}
        <div class="card" style="margin-bottom:14px;">
            <div class="card-header"><span class="card-title">Tambah Barang</span></div>
            <div class="card-body">
                <div style="position:relative;">
                    <input type="text" id="search-barang" placeholder="Ketik nama atau kode barang..."
                        autocomplete="off"
                        style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                    <div id="hasil-cari" style="display:none;position:absolute;top:100%;left:0;right:0;background:var(--bg-white);border:var(--border);border-radius:var(--radius-md);margin-top:4px;z-index:100;max-height:220px;overflow-y:auto;box-shadow:0 4px 16px rgba(0,0,0,.08);"></div>
                </div>
            </div>
        </div>

        {{-- TABEL ITEM --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Item Transaksi</span></div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="border-bottom:var(--border);">
                            <th style="padding:10px 18px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Barang</th>
                            <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;width:80px;">Qty</th>
                            <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;width:120px;">Harga</th>
                            <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;width:70px;">Diskon%</th>
                            <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;width:120px;">Subtotal</th>
                            <th style="padding:10px 12px;width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="tabel-item">
                        <tr id="row-kosong">
                            <td colspan="6" style="padding:30px;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada barang ditambahkan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="padding:12px 18px;border-top:var(--border);display:flex;justify-content:flex-end;">
                <div style="font-size:14px;font-weight:600;color:var(--text-primary);">
                    Total: <span id="grand-total" style="color:var(--brand);">Rp 0</span>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: INFO TRANSAKSI --}}
    <div class="card" style="position:sticky;top:70px;">
        <div class="card-header"><span class="card-title">Detail Transaksi</span></div>
        <div class="card-body">

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Nama Pelanggan</label>
                <input type="text" name="pelanggan" placeholder="Warung Bu Sari, Toko Pak Hendra..."
                    style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Status Pembayaran</label>
                <select name="status"
                    style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);background:var(--bg-white);outline:none;">
                    <option value="lunas">Lunas</option>
                    <option value="piutang">Piutang (belum bayar)</option>
                </select>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Total Belanja</label>
                <div id="display-total" style="padding:8px 12px;background:var(--bg-page);border-radius:var(--radius-md);font-size:16px;font-weight:600;color:var(--brand);">Rp 0</div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Uang Bayar (Rp)</label>
                <input type="text" id="input-bayar" placeholder="0" 
                    oninput="formatInputBayar(); hitungKembalian()"
                    style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;">
                <input type="hidden" name="total_bayar" id="input-bayar-value">
            </div>

            <div style="margin-bottom:16px;padding:10px 12px;background:var(--success-bg);border-radius:var(--radius-md);">
                <div style="font-size:11px;color:var(--success-text);margin-bottom:2px;">Kembalian</div>
                <div id="display-kembalian" style="font-size:16px;font-weight:600;color:var(--success-text);">Rp 0</div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-bottom:5px;">Catatan (opsional)</label>
                <textarea name="catatan" rows="2"
                    style="width:100%;padding:8px 12px;border:var(--border);border-radius:var(--radius-md);font-size:13px;font-family:var(--font);color:var(--text-primary);outline:none;resize:none;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:10px;" id="btn-simpan" disabled>
                Simpan Transaksi
            </button>
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;padding:10px;margin-top:8px;">Batal</a>
        </div>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
let items = [];
let grandTotal = 0;

// ── CARI BARANG ──────────────────────────────────────────────────────────
const inputSearch = document.getElementById('search-barang');
const hasilCari   = document.getElementById('hasil-cari');

let debounceTimer;
inputSearch.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const q = this.value.trim();
    if (q.length < 1) { hasilCari.style.display = 'none'; return; }
    debounceTimer = setTimeout(() => cariBarang(q), 250);
});

async function cariBarang(q) {
    const res  = await fetch(`/api/barang/search?q=${encodeURIComponent(q)}`);
    const data = await res.json();
    if (!data.length) { hasilCari.style.display = 'none'; return; }

    hasilCari.innerHTML = data.map(b => `
        <div onclick="tambahItem(${b.id}, '${b.nama_barang.replace(/'/g,"\\'")}', ${b.harga_jual}, ${b.stok}, '${b.satuan}')"
            style="padding:10px 14px;cursor:pointer;border-bottom:1px solid #F5F5F2;transition:background .1s;"
            onmouseover="this.style.background='#F5F5F2'" onmouseout="this.style.background=''">
            <div style="font-size:13px;font-weight:500;color:var(--text-primary);">${b.nama_barang}</div>
            <div style="font-size:11px;color:var(--text-muted);">${b.kode_barang} · Stok: ${b.stok} ${b.satuan} · Rp ${formatRp(b.harga_jual)}</div>
        </div>
    `).join('');
    hasilCari.style.display = 'block';
}

document.addEventListener('click', e => {
    if (!hasilCari.contains(e.target) && e.target !== inputSearch) {
        hasilCari.style.display = 'none';
    }
});

// ── TAMBAH ITEM ──────────────────────────────────────────────────────────
function tambahItem(id, nama, harga, stok, satuan) {
    hasilCari.style.display = 'none';
    inputSearch.value = '';

    const ada = items.find(i => i.id === id);
    if (ada) { alert(`${nama} sudah ada di daftar. Ubah qty langsung di tabel.`); return; }

    items.push({ id, nama, harga, stok, satuan, qty: 1, diskon: 0 });
    renderTabel();
}

function renderTabel() {
    const tbody = document.getElementById('tabel-item');
    if (!items.length) {
        tbody.innerHTML = '<tr id="row-kosong"><td colspan="6" style="padding:30px;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada barang ditambahkan</td></tr>';
        updateTotal();
        return;
    }

    tbody.innerHTML = items.map((item, idx) => {
        const subtotal = Math.round(item.qty * item.harga * (1 - item.diskon / 100));
        return `
        <tr style="border-bottom:1px solid #F5F5F2;">
            <td style="padding:10px 18px;">
                <div style="font-size:13px;font-weight:500;color:var(--text-primary);">${item.nama}</div>
                <div style="font-size:11px;color:var(--text-muted);">Stok: ${item.stok} ${item.satuan}</div>
                <input type="hidden" name="items[${idx}][barang_id]" value="${item.id}">
                <input type="hidden" name="items[${idx}][harga_satuan]" value="${item.harga}">
            </td>
            <td style="padding:10px 12px;text-align:center;">
                <input type="number" name="items[${idx}][qty]" value="${item.qty}" min="1" max="${item.stok}"
                    onchange="ubahQty(${idx}, this.value)"
                    style="width:60px;padding:5px 8px;border:var(--border);border-radius:var(--radius-md);text-align:center;font-size:13px;font-family:var(--font);outline:none;">
            </td>
            <td style="padding:10px 12px;text-align:right;color:var(--text-secondary);">Rp ${formatRp(item.harga)}</td>
            <td style="padding:10px 12px;text-align:center;">
                <input type="number" name="items[${idx}][diskon]" value="${item.diskon}" min="0" max="100"
                    onchange="ubahDiskon(${idx}, this.value)"
                    style="width:54px;padding:5px 8px;border:var(--border);border-radius:var(--radius-md);text-align:center;font-size:13px;font-family:var(--font);outline:none;">
            </td>
            <td style="padding:10px 12px;text-align:right;font-weight:600;color:var(--text-primary);">Rp ${formatRp(subtotal)}</td>
            <td style="padding:10px 12px;text-align:center;">
                <button type="button" onclick="hapusItem(${idx})"
                    style="background:none;border:none;cursor:pointer;color:var(--danger-text);font-size:16px;line-height:1;">×</button>
            </td>
        </tr>`;
    }).join('');

    updateTotal();
}

function ubahQty(idx, val) {
    const qty = Math.max(1, Math.min(parseInt(val) || 1, items[idx].stok));
    items[idx].qty = qty;
    renderTabel();
}

function ubahDiskon(idx, val) {
    items[idx].diskon = Math.max(0, Math.min(100, parseInt(val) || 0));
    renderTabel();
}

function hapusItem(idx) {
    items.splice(idx, 1);
    renderTabel();
}

function updateTotal() {
    grandTotal = items.reduce((sum, item) => {
        return sum + Math.round(item.qty * item.harga * (1 - item.diskon / 100));
    }, 0);

    document.getElementById('grand-total').textContent  = 'Rp ' + formatRp(grandTotal);
    document.getElementById('display-total').textContent = 'Rp ' + formatRp(grandTotal);
    document.getElementById('btn-simpan').disabled = items.length === 0;
    hitungKembalian();
}

function hitungKembalian() {
    const bayar     = parseInt(document.getElementById('input-bayar-value').value) || 0;
    const kembalian = bayar - grandTotal;
    const el        = document.getElementById('display-kembalian');
    el.textContent  = 'Rp ' + formatRp(Math.max(0, kembalian));
    el.style.color  = kembalian < 0 ? 'var(--danger-text)' : 'var(--success-text)';
}

function formatInputBayar() {
    const input = document.getElementById('input-bayar');
    const hidden = document.getElementById('input-bayar-value');
    
    // Hapus semua karakter selain angka
    const angkaOnly = input.value.replace(/\D/g, '');
    
    // Simpan nilai numeric di hidden input
    hidden.value = angkaOnly;
    
    // Format dengan separator period (indonesian format)
    if (angkaOnly) {
        input.value = parseInt(angkaOnly).toLocaleString('id-ID');
    }
}

function formatRp(n) {
    return Math.round(n).toLocaleString('id-ID');
}
</script>
@endpush