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
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfWeek = Carbon::today()->subDays(6)->startOfDay();

        $totalBarang = Barang::count();
        $barangBaruBulanIni = Barang::where('created_at', '>=', $startOfMonth)->count();

        $transaksiHariIni = Transaksi::whereDate('created_at', $today)->count();
        $transaksiKemarin = Transaksi::whereDate('created_at', $yesterday)->count();
        $selisihTransaksi = $transaksiHariIni - $transaksiKemarin;

        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'lunas')->sum('total_harga');
        $pendapatanKemarin = Transaksi::whereDate('created_at', $yesterday)
            ->where('status', 'lunas')->sum('total_harga');
        $persenPendapatan = $pendapatanKemarin > 0
            ? round((($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100)
            : 0;

        $stockAlertCount = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
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

        $stockMovementRaw = DB::table('stock_movements')
            ->whereBetween('created_at', [$startOfWeek, Carbon::now()->endOfDay()])
            ->selectRaw('DATE(created_at) as tanggal, SUM(CASE WHEN direction = "in" THEN qty ELSE 0 END) as total_in, SUM(CASE WHEN direction = "out" THEN qty ELSE 0 END) as total_out')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $stockMovement = collect();
        for ($date = $startOfWeek->copy(); $date->lte(Carbon::today()); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $row = $stockMovementRaw->get($key);
            $stockMovement->push([
                'tanggal' => $date->isoFormat('dd'),
                'total_in' => $row->total_in ?? 0,
                'total_out' => $row->total_out ?? 0,
            ]);
        }

        $currentStockTop = Barang::orderByDesc('stok')->limit(6)->get();

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
            'stockMovement',
            'currentStockTop',
        ))->with('title', 'Dashboard');
    }
}
