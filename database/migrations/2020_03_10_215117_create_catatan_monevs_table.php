<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatatanMonevsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catatan_monev', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tindaklanjut')->default(0)->nullable();
            $table->integer('id_rekomendasi')->default(0)->nullable();
            $table->text('catatan_monev')->nullable();
            $table->integer('pic_2')->default(0)->nullable();
            $table->integer('pic_1')->default(0)->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catatan_monev');
    }
}
