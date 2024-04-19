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
        Schema::create('barang_pinjam', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acara_id');
            $table->string('jenis_barang');
            $table->bigInteger('jml_barang');
            $table->text('catatan');
            $table->string('storage');
            $table->string('kepemilikan');
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_pinjam');
    }
};
