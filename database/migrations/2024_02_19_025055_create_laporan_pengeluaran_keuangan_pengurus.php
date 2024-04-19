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
        Schema::create('laporan_pengeluaran_keuangan_pengurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengurus_id');
            $table->string('nama_pengeluaran');
            $table->unsignedBigInteger('jenis');
            $table->date('tgl');
            $table->text('catatan')->nullable();
            $table->integer('jumlah_brg')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->unsignedBigInteger('storage_minio')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pengeluaran_keuangan_pengurus');
    }
};
