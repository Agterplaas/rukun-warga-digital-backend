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
        Schema::create('warga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk');
            $table->string('nik');
            $table->string('nama');
            $table->unsignedBigInteger('jenis_kelamin');
            $table->date('tgl_lahir');
            $table->string('alamat_ktp');
            $table->string('blok');
            $table->integer('nomor');
            $table->integer('rt');
            $table->unsignedBigInteger('agama');
            $table->string('pekerjaan');
            $table->string('no_telp');
            $table->unsignedBigInteger('status_pekerjaan');
            $table->unsignedBigInteger('status_warga');
            $table->unsignedBigInteger('status_kawin');
            $table->unsignedBigInteger('status_sosial');
            $table->text('catatan');
            $table->unsignedBigInteger('kk_pj');
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
        Schema::dropIfExists('warga');
    }
};
