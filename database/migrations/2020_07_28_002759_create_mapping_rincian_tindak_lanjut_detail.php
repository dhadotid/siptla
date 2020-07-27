<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMappingRincianTindakLanjutDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapping_rincian_tindak_lanjut_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('id_rekomendasi')->nullable()->default(0);
            $table->integer('id_rincian')->nullable()->default(0);
            $table->integer('id_tindak_lanjut_rincian')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rekomendasi_mapping_notification');
    }
}
