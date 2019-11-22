<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterKepalaDinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_pimpinan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama')->nullable();
            $table->integer('dinas_id')->nullable()->default(0);
            $table->integer('user_id')->nullable()->default(0);
            $table->integer('flag')->nullable()->default(0);
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
        Schema::dropIfExists('master_pimpinan');
    }
}
