<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPksTableLanjutRincian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_rincian', function (Blueprint $table) {
            $table->string('no_pks')->nullable();
            $table->date('tanggal_pks')->nullable();
            $table->string('periode_pks')->nullable();
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
            $table->dropColumn('no_pks');
            $table->dropColumn('tanggal_pks');
            $table->dropColumn('periode_pks');
        });
    }
}
