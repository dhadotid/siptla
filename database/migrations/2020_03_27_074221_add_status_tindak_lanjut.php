<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusTindakLanjut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->integer('create_oleh_pic_unit')->nullable()->default(0);
            $table->integer('belum_direview_spi')->nullable()->default(0);
            $table->integer('sedang_direview_spi')->nullable()->default(0);
            $table->integer('sudah_direview_spi')->nullable()->default(0);
            $table->integer('sudah_dipublish_spi')->nullable()->default(0);
            $table->integer('status_tindaklanjut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->dropColumn('create_oleh_pic_unit');
            $table->dropColumn('belum_direview_spi');
            $table->dropColumn('sedang_direview_spi');
            $table->dropColumn('sudah_direview_spi');
            $table->dropColumn('sudah_dipublish_spi');
            $table->dropColumn('status_tindaklanjut');
        });
    }
}
