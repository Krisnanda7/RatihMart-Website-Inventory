<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today        = Carbon::today();
        $yesterday    = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        $totalBarang        = Barang::count();
        $barangBaruBulanIni = Barang::where('created_at', '>=', $startOfMonth)->count();

        $transaksiHariIni = Transaksi::whereDate('created_at', $today)->count();
        $transaksiKemarin = Transaksi::whereDate('created_at', $yesterday)->count();
        $selisihTransaksi = $transaksiHariIni - $transaksiKemarin;

        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'lunas')->sum('total_harga');
        $pendapatanKemarin = Transaksi::whereDate('created_at', $yesterday)
            ->where('status', 'lunas')->sum('total_harga');
        $persenPendapatan  = $pendapatanKemarin > 0
            ? round((($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100)
            : 0;

        $stockAlertCount  = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
        $barangStokRendah = Barang::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')->limit(10)->get();

        $transaksiTerbaru = Transaksi::latest()->limit(7)->get();

        $kategoriPenjualan = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'detail_transaksis.barang_id', '=', 'barangs.id')
            ->where('transaksis.created_at', '>=', $startOfMonth)
            ->select(
                'barangs.kategori',
                DB::raw('SUM(detail_transaksis.subtotal) as total')
            )
            ->groupBy('barangs.kategori')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return view('dashboard.index', compact(
            'totalBarang',
            'barangBaruBulanIni',
            'transaksiHariIni',
            'selisihTransaksi',
            'pendapatanHariIni',
            'persenPendapatan',
            'stockAlertCount',
            'barangStokRendah',
            'transaksiTerbaru',
            'kategoriPenjualan',
        ))->with('title', 'Dashboard');
    }
}