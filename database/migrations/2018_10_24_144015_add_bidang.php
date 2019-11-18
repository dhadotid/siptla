<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBidang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_temuan', function (Blueprint $table) {
            $table->integer('pengawasan_id')->nullable()->default(0);
            $table->string('no_pengawasan')->nullable();
            $table->date('tgl_pengawasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_temuan', function (Blueprint $table) {
            //
        });
    }
}
