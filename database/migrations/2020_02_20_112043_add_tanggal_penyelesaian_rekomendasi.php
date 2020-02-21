<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTanggalPenyelesaianRekomendasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_rekomendasi', function (Blueprint $table) {
            $table->date('tanggal_penyelesaian')->nullable();
            $table->string('published')->nullable()->default(0);
            $table->string('nomor_rekomendasi')->nullable()->after('rekomendasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_rekomendasi', function (Blueprint $table) {
            $table->dropColumn('tanggal_penyelesaian');
            $table->dropColumn('published');
            $table->dropColumn('nomor_rekomendasi');
        });
    }
}
