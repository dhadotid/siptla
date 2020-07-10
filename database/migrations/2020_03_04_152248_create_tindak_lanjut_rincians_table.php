<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTindakLanjutRinciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tindak_lanjut_rincian', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('id_rekomendasi')->nullable()->default(0);
            $table->integer('unit_kerja_id')->nullable()->default(0);
            $table->integer('id_tindak_lanjut')->nullable()->default(0);
            $table->integer('tahun')->nullable()->default(0);
            $table->string('jenis')->nullable();
            $table->text('tindak_lanjut_rincian')->nullable();
            $table->double('nilai')->nullable()->default(0);
            $table->date('tanggal')->nullable();
            // $table->date('tanggal_penutupan')->nullable();
            $table->string('jenis_setoran')->nullable();
            $table->string('bank_tujuan')->nullable();
            $table->string('no_referensi')->nullable();
            $table->string('jenis_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->double('saldo_akhir')->nullable()->default(0);
            $table->double('jumlah_rekomendasi')->nullable()->default(0);
            $table->string('dokumen_pendukung')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('tindak_lanjut_rincian');
    }
}
