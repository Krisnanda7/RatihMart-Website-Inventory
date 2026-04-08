<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // ── LAPORAN PENJUALAN ─────────────────────────────────────────────────
    public function penjualan(Request $request)
    {
        $periode = $request->get('periode', 'harian');
        $dari    = $request->get('dari', Carbon::today()->format('Y-m-d'));
        $sampai  = $request->get('sampai', Carbon::today()->format('Y-m-d'));

        // Jika periode bulanan, set range ke 1 bulan penuh
        if ($periode === 'bulanan') {
            $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));
            $dari   = Carbon::parse($bulan)->startOfMonth()->format('Y-m-d');
            $sampai = Carbon::parse($bulan)->endOfMonth()->format('Y-m-d');
        }

        // ── RINGKASAN ──
        $ringkasan = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(CASE WHEN status = "lunas" THEN total_harga ELSE 0 END) as total_pendapatan,
                SUM(CASE WHEN status = "piutang" THEN total_harga ELSE 0 END) as total_piutang,
                SUM(CASE WHEN status = "batal" THEN 1 ELSE 0 END) as total_batal
            ')
            ->first();

        // ── GRAFIK HARIAN (dalam range yang dipilih) ──
        $grafikHarian = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->where('status', 'lunas')
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as total, COUNT(*) as jumlah')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // ── BARANG TERLARIS ──
        $barangTerlaris = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$dari, $sampai])
            ->where('transaksis.status', '!=', 'batal')
            ->selectRaw('
                barangs.nama_barang,
                barangs.satuan,
                SUM(detail_transaksis.qty) as total_qty,
                SUM(detail_transaksis.subtotal) as total_nilai
            ')
            ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.satuan')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // ── PENJUALAN PER KATEGORI ──
        $perKategori = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$dari, $sampai])
            ->where('transaksis.status', '!=', 'batal')
            ->selectRaw('barangs.kategori, SUM(detail_transaksis.subtotal) as total')
            ->groupBy('barangs.kategori')
            ->orderByDesc('total')
            ->get();

        // ── DAFTAR TRANSAKSI ──
        $transaksi = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stockAlertCount = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('laporan.penjualan', compact(
            'periode', 'dari', 'sampai',
            'ringkasan', 'grafikHarian',
            'barangTerlaris', 'perKategori',
            'transaksi', 'stockAlertCount',
        ))->with('title', 'Laporan Penjualan');
    }

    // ── LAPORAN LABA RUGI ─────────────────────────────────────────────────
    public function labaRugi(Request $request)
    {
        $bulan  = $request->get('bulan', Carbon::now()->format('Y-m'));
        $dari   = Carbon::parse($bulan)->startOfMonth()->format('Y-m-d');
        $sampai = Carbon::parse($bulan)->endOfMonth()->format('Y-m-d');

        // ── PENDAPATAN (dari transaksi lunas) ──
        $pendapatan = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->where('status', 'lunas')
            ->sum('total_harga');

        // ── HPP: Harga Pokok Penjualan (qty × harga_beli per item) ──
        $hpp = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$dari, $sampai])
            ->where('transaksis.status', 'lunas')
            ->selectRaw('SUM(detail_transaksis.qty * barangs.harga_beli) as total_hpp')
            ->value('total_hpp') ?? 0;

        $labaKotor    = $pendapatan - $hpp;
        $marginPersen = $pendapatan > 0 ? round(($labaKotor / $pendapatan) * 100, 1) : 0;

        // ── LABA KOTOR PER KATEGORI ──
        $labaPerKategori = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$dari, $sampai])
            ->where('transaksis.status', 'lunas')
            ->selectRaw('
                barangs.kategori,
                SUM(detail_transaksis.subtotal) as pendapatan,
                SUM(detail_transaksis.qty * barangs.harga_beli) as hpp,
                SUM(detail_transaksis.subtotal - (detail_transaksis.qty * barangs.harga_beli)) as laba
            ')
            ->groupBy('barangs.kategori')
            ->orderByDesc('laba')
            ->get();

        // ── LABA PER BARANG (top 10) ──
        $labaPerBarang = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$dari, $sampai])
            ->where('transaksis.status', 'lunas')
            ->selectRaw('
                barangs.nama_barang,
                barangs.kategori,
                SUM(detail_transaksis.qty) as qty_terjual,
                SUM(detail_transaksis.subtotal) as pendapatan,
                SUM(detail_transaksis.qty * barangs.harga_beli) as hpp,
                SUM(detail_transaksis.subtotal - (detail_transaksis.qty * barangs.harga_beli)) as laba
            ')
            ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kategori')
            ->orderByDesc('laba')
            ->limit(10)
            ->get();

        // ── TREND LABA HARIAN ──
        $trendHarian = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$dari, $sampai])
            ->where('transaksis.status', 'lunas')
            ->selectRaw('
                DATE(transaksis.created_at) as tanggal,
                SUM(detail_transaksis.subtotal) as pendapatan,
                SUM(detail_transaksis.qty * barangs.harga_beli) as hpp,
                SUM(detail_transaksis.subtotal - (detail_transaksis.qty * barangs.harga_beli)) as laba
            ')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Bulan sebelumnya untuk perbandingan
        $bulanLaluDari   = Carbon::parse($bulan)->subMonth()->startOfMonth()->format('Y-m-d');
        $bulanLaluSampai = Carbon::parse($bulan)->subMonth()->endOfMonth()->format('Y-m-d');

        $pendapatanBulanLalu = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$bulanLaluDari, $bulanLaluSampai])
            ->where('status', 'lunas')->sum('total_harga');

        $hppBulanLalu = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->whereBetween(DB::raw('DATE(transaksis.created_at)'), [$bulanLaluDari, $bulanLaluSampai])
            ->where('transaksis.status', 'lunas')
            ->selectRaw('SUM(detail_transaksis.qty * barangs.harga_beli) as total_hpp')
            ->value('total_hpp') ?? 0;

        $labaBulanLalu    = $pendapatanBulanLalu - $hppBulanLalu;
        $persenLabaChange = $labaBulanLalu > 0
            ? round((($labaKotor - $labaBulanLalu) / $labaBulanLalu) * 100, 1)
            : 0;

        $stockAlertCount = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('laporan.laba-rugi', compact(
            'bulan', 'dari', 'sampai',
            'pendapatan', 'hpp', 'labaKotor', 'marginPersen',
            'labaPerKategori', 'labaPerBarang', 'trendHarian',
            'pendapatanBulanLalu', 'labaBulanLalu', 'persenLabaChange',
            'stockAlertCount',
        ))->with('title', 'Laporan Laba Rugi');
    }
}