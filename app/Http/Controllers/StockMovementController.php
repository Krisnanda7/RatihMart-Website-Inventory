<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('barang')->latest();

        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        if ($request->filled('barang')) {
            $query->whereHas('barang', function ($query) use ($request) {
                $query->where('nama_barang', 'like', "%{$request->barang}%")
                    ->orWhere('kode_barang', 'like', "%{$request->barang}%");
            });
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $stockMovements   = $query->paginate(15)->withQueryString();
        $stockAlertCount  = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('stock-movements.index', compact('stockMovements', 'stockAlertCount'))
            ->with('title', 'Manajemen Stok');
    }

    public function create()
    {
        $barangs         = Barang::orderBy('nama_barang')->get();
        $stockAlertCount = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('stock-movements.create', compact('barangs', 'stockAlertCount'))
            ->with('title', 'Tambah Stok');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barangs,id'],
            'direction' => ['required', Rule::in(['in', 'out'])],
            'qty'       => ['required', 'integer', 'min:1'],
            'notes'     => ['nullable', 'string', 'max:255'],
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);

        DB::transaction(function () use ($barang, $validated) {
            if ($validated['direction'] === 'out' && $barang->stok < $validated['qty']) {
                abort(422, 'Stok tidak cukup untuk pengurangan ini.');
            }

            $barang->{$validated['direction'] === 'in' ? 'increment' : 'decrement'}('stok', $validated['qty']);

            StockMovement::create([
                'barang_id'  => $barang->id,
                'direction'  => $validated['direction'],
                'qty'        => $validated['qty'],
                'notes'      => $validated['notes'] ?? 'Penyesuaian stok manual',
            ]);
        });

        return redirect()->route('stock-movements.index')
            ->with('success', "Stok \"{$barang->nama_barang}\" berhasil diperbarui.");
    }
}
