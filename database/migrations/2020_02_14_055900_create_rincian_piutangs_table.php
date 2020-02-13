<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRincianPiutangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rincian_piutang', function (Blueprint $table) {
             $table->increments('id');
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('id_rekomendasi')->nullable()->default(0);
            $table->integer('unit_kerja_id')->nullable()->default(0);
            $table->string('unit_kerja')->nullable();
            $table->string('pelanggan')->nullable();
            $table->string('tagihan')->nullable();
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
        Schema::dropIfExists('rincian_piutang');
    }
}
