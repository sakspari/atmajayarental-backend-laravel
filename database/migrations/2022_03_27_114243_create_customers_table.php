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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('picture')->nullable();
            $table->string('address');
            $table->date('birthdate');
            $table->boolean('gender');
            $table->string('email');
            $table->string('phone');
            $table->string('sim')->nullable();
            $table->string('idcard')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
