<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('review_id')->nullable()->default(0);
            $table->integer('id_lhp')->nullable()->default(0);
            $table->integer('reviewer_id')->nullable()->default(0);
            $table->longText('review')->nullable();
            $table->integer('user_id')->nullable()->default(0);
            $table->longText('tanggapan')->nullable();
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
        Schema::dropIfExists('review');
    }
}
