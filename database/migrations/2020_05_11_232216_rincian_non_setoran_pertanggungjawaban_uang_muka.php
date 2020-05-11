<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RincianNonSetoranPertanggungjawabanUangMuka extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rincian_non_setoran_pertanggungjawaban_uang_muka', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('id_rekomendasi')->nullable()->default(0);
            $table->integer('unit_kerja_id')->nullable()->default(0);
            $table->integer('id_tindak_lanjut')->nullable()->default(0);
            $table->string('unit_kerja')->nullable();
            
            $table->string('no_invoice')->nullable();
            $table->date('tgl_um')->nullable();
            $table->string('jumlah_um')->nullable()->default(0);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('rincian_non_setoran_pertanggungjawaban_uang_muka');
    }
}
