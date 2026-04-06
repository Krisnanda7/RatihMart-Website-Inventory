<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('qty')->unsigned();
            $table->unsignedBigInteger('harga_satuan');
            $table->tinyInteger('diskon')->default(0);
            $table->unsignedBigInteger('subtotal');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('transaksi_id')
                  ->references('id')->on('transaksis')
                  ->onDelete('cascade');

            $table->foreign('barang_id')
                  ->references('id')->on('barangs')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
