<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreatePejabatTandatangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pejabat_tandatangan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip')->nullable()->unique();
            $table->string('nama')->nullable();
            $table->string('jabatan')->nullable();
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
        Schema::dropIfExists('pejabat_tandatangan');
    }
}
