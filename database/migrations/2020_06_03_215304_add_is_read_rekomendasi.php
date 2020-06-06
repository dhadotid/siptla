<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReadRekomendasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_rekomendasi', function (Blueprint $table) {
            $table->integer('is_read')->nullable()->default(0)
            ->comment('0 = Belum di read | 1 = Sudah di read');
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
            $table->dropColumn('is_read');
        });
    }
}
