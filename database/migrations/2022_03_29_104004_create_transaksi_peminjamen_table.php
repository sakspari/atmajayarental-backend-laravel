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
        Schema::create('transaksi_peminjaman', function (Blueprint $table) {
            $table->string('id_transaksi')->primary();
            $table->string('id_mobil');
            $table->foreign('id_mobil')
                ->references('id_mobil')
                ->on('mobils')
                ->onDelete('cascade');
            $table->string('id_customer');
            $table->foreign('id_customer')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
            $table->string('id_driver')->nullable();
            $table->foreign('id_driver')
                ->references('id')
                ->on('drivers')
                ->onDelete('cascade');
            $table->string('id_pegawai')->nullable();
            $table->foreign('id_pegawai')
                ->references('id')
                ->on('pegawais')
                ->onDelete('cascade');
            $table->string('kode_promo')->nullable();
            $table->foreign('kode_promo')
                ->references('kode_promo')
                ->on('promos')
                ->onDelete('cascade');
            $table->dateTime('waktu_transaksi');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->dateTime('waktu_pengembalian')->nullable();
            $table->integer('subtotal_mobil')->nullable();
            $table->integer('subtotal_driver')->nullable();
            $table->integer('total_denda')->nullable();
            $table->integer('total_diskon')->nullable();
            $table->integer('grand_total')->nullable();
            $table->boolean('metode_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_transaksi');
            $table->float('rating_driver')->nullable();
            $table->string('review_driver')->nullable();
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
        Schema::dropIfExists('transaksi_peminjamen');
    }
};
