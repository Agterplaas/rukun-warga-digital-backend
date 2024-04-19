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
        Schema::create('laporan_pemasukan_keuangan_pengurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengurus_id');
            $table->string('nama_pemasukan');
            $table->text('catatan')->nullable();
            $table->date('tgl');
            $table->decimal('nominal', 10, 2);
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
        Schema::dropIfExists('laporan_pemasukan_keuangan_pengurus');
    }
};
