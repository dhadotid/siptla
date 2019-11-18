<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaftarTemuansTable extends Migration
{
    /**
     * Run the migrations.
     *
  
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_temuan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aparat_id')->nullable()->default(0);
            $table->integer('dinas_id')->nullable()->default(0);
            $table->integer('tahun')->nullable()->default(0);
            $table->integer('flag')->nullable()->default(0);
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
        Schema::dropIfExists('daftar_temuan');
    }
}
