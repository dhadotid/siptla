<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailTemuansTable extends Migration
{
    /**
     * Run the migrations.
     *  Status :
     *  B = Belum Dilakukan Tindak Lanjut
     *  S = Selesai
     *  DP = Dalam Proses Tindak Lanjut
     * @return void
     */
    public function up()
    {
        Schema::create('detail_temuan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('daftar_id')->nullable()->default(0);
            $table->integer('pengawasan_id')->nullable()->default(0);
            $table->integer('temuan_id')->nullable()->default(0);
            $table->integer('sebab_id')->nullable()->default(0);
            $table->integer('rekomendasi_id')->nullable()->default(0);
            $table->string('no_pengawasan')->nullable();
            $table->date('tgl_pengawasan')->nullable();
            $table->text('uraian_temuan')->nullable();
            $table->text('uraian_rekomendasi')->nullable();
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
        Schema::dropIfExists('detail_temuan');
    }
}
