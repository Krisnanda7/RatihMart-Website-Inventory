<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'barang_id',
        'direction',
        'qty',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
