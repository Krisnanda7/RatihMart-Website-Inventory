<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'detail_transaksis';

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'qty',
        'harga_satuan',
        'diskon',
        'subtotal',
    ];

    protected $casts = [
        'qty'          => 'integer',
        'harga_satuan' => 'integer',
        'diskon'       => 'integer',
        'subtotal'     => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
