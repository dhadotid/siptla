<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterDinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_fakultas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_fakultas')->nullable();
            $table->string('singkatan')->nullable();
            $table->string('alamat')->nullable();
            $table->integer('flag')->nullable()->default(0);
            $table->string('nama_slug')->nullable();
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
        Schema::dropIfExists('master_fakultas');
    }
}
