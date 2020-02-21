<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagSeniorUnitkerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_lhp', function (Blueprint $table) {
            $table->integer('flag_senior')->nullable()->default(0);
            $table->integer('flag_unit_kerja')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_lhp', function (Blueprint $table) {
            $table->dropColumn('flag_senior');
            $table->dropColumn('flag_unit_kerja');
        });
    }
}
