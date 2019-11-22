<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateJenisAudirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_audit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenis_audit')->nullable()->unique();
            $table->integer('flag')->nullable()->default(0);
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
        Schema::dropIfExists('jenis_audit');
    }
}
