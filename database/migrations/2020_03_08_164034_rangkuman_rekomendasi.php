<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RangkumanRekomendasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_rekomendasi', function (Blueprint $table) {
             $table->text('rangkuman_rekomendasi')->nullable();
            $table->string('file_pendukung')->nullable();
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
            $table->dropColumn('rangkuman_rekomendasi');
            $table->dropColumn('file_pendukung');
        });
    }
}
