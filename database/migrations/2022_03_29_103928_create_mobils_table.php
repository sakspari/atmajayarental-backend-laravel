<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobils', function (Blueprint $table) {
            $table->string('id_mobil')->primary();
            $table->unsignedBigInteger('id_mitra')->nullable();
            $table->foreign('id_mitra')
                ->references('id')
                ->on('mitras')
                ->onDelete('cascade');
            $table->string('plat_mobil');
            $table->string('no_stnk');
            $table->string('nama_mobil');
            $table->string('tipe_mobil');
            $table->boolean('jenis_aset');
            $table->string('jenis_transmisi');
            $table->string('jenis_bahan_bakar');
            $table->float('volume_bahan_bakar')->nullable();
            $table->string('warna_mobil')->nullable();
            $table->string('fasilitas_mobil')->nullable();
            $table->float('volume_bagasi')->nullable();
            $table->integer('kapasitas_penumpang')->nullable();
            $table->integer('harga_sewa');
            $table->date('servis_terakhir')->nullable();
            $table->string('foto_mobil')->nullable();
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobils');
    }
};
