<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTanggalPenutupanTableLanjutRincian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_rincian', function (Blueprint $table) {
            $table->date('tanggal_penutupan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tindak_lanjut_rincian', function (Blueprint $table) {
            $table->dropColumn('tanggal_penutupan');
        });
    }
}
