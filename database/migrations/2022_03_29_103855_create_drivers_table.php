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
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('picture')->nullable();
            $table->string('address');
            $table->string('birthdate');
            $table->boolean('gender');
            $table->string('email');
            $table->string('phone');
            $table->string('language');
            $table->integer('price');
            $table->string('file_sim');
            $table->string('file_bebas_napza');
            $table->string('file_sk_jiwa');
            $table->string('file_sk_jasmani');
            $table->string('file_skck');
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('drivers');
    }
};
