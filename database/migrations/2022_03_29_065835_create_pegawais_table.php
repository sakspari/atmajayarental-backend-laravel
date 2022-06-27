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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('role_id');
            $table->foreign('role_id')
                ->references('role_id')
                ->on('roles')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('picture')->nullable();
            $table->string('birthdate');
            $table->string('gender');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
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
        Schema::dropIfExists('pegawais');
    }
};
