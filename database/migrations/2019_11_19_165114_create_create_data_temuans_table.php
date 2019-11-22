<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateDataTemuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_temuan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_lhp')->nullable()->default(0);
            $table->string('no_lhp')->nullable();
            $table->string('no_temuan')->nullable();
            $table->text('temuan')->nullable();
            $table->integer('jenis_temuan_id')->nullable()->default(0);
            $table->string('pic_temuan_id')->nullable();
            $table->integer('level_resiko_id')->nullable()->default(0);
            $table->double('nominal')->nullable()->default(0);
            
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
        Schema::dropIfExists('data_temuan');
    }
}
