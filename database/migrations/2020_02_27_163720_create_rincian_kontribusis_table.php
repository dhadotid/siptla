<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRincianKontribusisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rincian_kontribusi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('id_rekomendasi')->nullable()->default(0);
            $table->integer('unit_kerja_id')->nullable()->default(0);
            $table->integer('id_tindak_lanjut')->nullable()->default(0);
            $table->integer('tahun')->nullable()->default(0);
            $table->double('nilai_penerimaan')->nullable()->default(0);
            $table->string('unit_kerja')->nullable();
            $table->string('jenis_setoran')->nullable();
            $table->string('bank_tujuan')->nullable();
            $table->string('no_ref')->nullable();
            $table->string('jenis_rekening')->nullable();
            $table->string('dokumen_pendukung')->nullable();
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
        Schema::dropIfExists('rincian_kontribusi');
    }
}
