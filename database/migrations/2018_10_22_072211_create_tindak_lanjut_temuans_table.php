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
            $table->integer('detail_id')->nullable()->default(0);
            $table->string('status')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->integer('paraf_inspektorat')->nullable()->default(0);
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
        Schema::dropIfExists('tindak_lanjut_temuan');
    }
}
