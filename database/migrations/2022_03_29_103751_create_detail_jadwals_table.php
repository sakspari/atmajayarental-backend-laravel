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
        Schema::create('detail_jadwals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal');
            $table->string('id_pegawai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreign('id_jadwal')
                ->references('id')
                ->on('jadwals')
                ->onDelete('cascade');
            $table->foreign('id_pegawai')
                ->references('id')
                ->on('pegawais')
                ->onDelete('cascade');
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
        Schema::dropIfExists('detail_jadwals');
    }
};
