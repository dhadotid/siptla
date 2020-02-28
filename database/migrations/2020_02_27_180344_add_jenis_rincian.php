<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJenisRincian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->string('rincian')->nullable();
            $table->date('tgl_tindaklanjut')->nullable();
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
            $table->dropColumn('rincian');
            $table->dropColumn('tgl_tindaklanjut');
        });
    }
}
