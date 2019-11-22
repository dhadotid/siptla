<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateDaftarRekanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_rekanan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daftar_rekanan');
    }
}
