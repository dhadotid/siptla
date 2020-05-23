<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoRekPemindahanSaldoTableLanjutRincian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_rincian', function (Blueprint $table) {
            $table->string('no_rek_pemindah_saldo')->nullable();
            $table->string('nama_rekening_pemindah_saldo')->nullable();
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
            $table->dropColumn('no_rek_pemindah_saldo');
            $table->dropColumn('nama_rekening_pemindah_saldo');
        });
    }
}
