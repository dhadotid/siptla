<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateStatusRekomendasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_rekomendasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rekomendasi')->nullable()->unique();
            $table->text('desc')->nullable();
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
        Schema::dropIfExists('status_rekomendasi');
    }
}
