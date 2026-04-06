<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'deskripsi',
    ];

    protected $casts = [
        'harga_beli'   => 'integer',
        'harga_jual'   => 'integer',
        'stok'         => 'integer',
        'stok_minimum' => 'integer',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0) return 'habis';
        if ($this->stok <= ($this->stok_minimum / 2)) return 'kritis';
        if ($this->stok <= $this->stok_minimum) return 'rendah';
        return 'aman';
    }
    
}
