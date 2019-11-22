<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreatePicUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pic_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level_pic')->nullable();
            $table->integer('bidang')->nullable()->default(0);
            $table->integer('fakultas')->nullable()->default(0);
            $table->integer('pic_1_flag')->nullable()->default(0);
            $table->integer('pic_2_flag')->nullable()->default(0);
            $table->string('nama_pic')->nullable()->unique();
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
        Schema::dropIfExists('pic_unit');
    }
}
