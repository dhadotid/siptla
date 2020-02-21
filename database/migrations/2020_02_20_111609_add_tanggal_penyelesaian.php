<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTanggalPenyelesaian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_temuan', function (Blueprint $table) {
            $table->date('tanggal_penyelesaian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_temuan', function (Blueprint $table) {
            $table->dropColumn('tanggal_penyelesaian');
        });
    }
}
