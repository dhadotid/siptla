<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateDataRekomendasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * jenis_temuan 
     * 1.  Selain Barang/Jasa
     * 2.  Barang/jasa
     */
    public function up()
    {
        Schema::create('data_rekomendasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_temuan')->nullable();
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('jenis_temuan')->nullable()->default(0);
            $table->double('nominal_rekomendasi')->nullable()->default(0);
            $table->text('rekomendasi')->nullable();
            $table->string('pic_1_temuan_id')->nullable();
            $table->string('pic_2_temuan_id')->nullable();
            $table->string('rekanan')->nullable();
            $table->integer('jangka_waktu_id')->nullable()->default(0);
            $table->integer('status_rekomendasi_id')->nullable()->default(0);
            $table->text('review_auditor')->nullable();
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
        Schema::dropIfExists('data_rekomendasi');
    }
}
