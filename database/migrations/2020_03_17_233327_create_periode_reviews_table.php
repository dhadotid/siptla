<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodeReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periode_review', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tanggal_mulai')->nullable()->default(4);
            $table->integer('tanggal_selesai')->nullable()->default(25);
            $table->integer('status')->nullable()->default(0);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('periode_review');
    }
}
