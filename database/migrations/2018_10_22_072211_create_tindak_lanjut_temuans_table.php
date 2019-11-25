<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTindakLanjutTemuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rekomendasi_id')->nullable()->default(0);
            $table->integer('temuan_id')->nullable()->default(0);
            $table->integer('lhp_id')->nullable()->default(0);
            $table->string('pic_1_id')->nullable()->default(0);
            $table->string('pic_2_id')->nullable()->default(0);
            $table->double('nilai')->nullable()->default(0);
            $table->string('status_review_pic_1')->nullable();
            $table->longText('hasil_review_pic_1')->nullable();
            $table->string('status_review_pic_2')->nullable();
            $table->longText('hasil_review_pic_2')->nullable();
            $table->longText('rangkuman')->nullable();
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
        Schema::dropIfExists('tindak_lanjut_temuan');
    }
}
