<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'pelanggan',
        'total_harga',
        'total_bayar',
        'kembalian',
        'status',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'total_harga' => 'integer',
        'total_bayar' => 'integer',
        'kembalian'   => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaksi) {
            $latest = static::whereDate('created_at', today())->count() + 1;
            $transaksi->kode_transaksi = 'TRX-' . now()->format('ymd') . '-' . str_pad($latest, 3, '0', STR_PAD_LEFT);
        });
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
