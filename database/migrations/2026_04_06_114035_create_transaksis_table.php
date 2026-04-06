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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->string('pelanggan', 100)->nullable();
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->unsignedBigInteger('total_bayar')->default(0);
            $table->bigInteger('kembalian')->default(0);
            $table->enum('status', ['lunas', 'piutang', 'batal'])->default('lunas');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
