<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaftarTemuansTable extends Migration
{
    /**
     * Run the migrations.
     *
  
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_lhp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_lhp')->nullable();
            $table->string('kode_lhp')->nullable();
            $table->string('judul_lhp')->nullable();
            $table->longText('review')->nullable();
            $table->integer('pemeriksa_id')->nullable()->nullable();
            $table->date('tanggal_lhp')->nullable();
            $table->integer('tahun_pemeriksa')->nullable();
            $table->integer('jenis_audit_id')->nullable()->default(0);
            $table->string('status_lhp')->nullable();
            $table->string('review_lhp')->nullable();
            $table->integer('create_flag')->nullable()->default(0);
            $table->integer('review_flag')->nullable()->default(0);
            $table->integer('publish_flag')->nullable()->default(0);
            $table->integer('user_input_id')->nullable()->default(0);
            $table->integer('flag_tindaklanjut_id')->nullable()->default(0);
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
        Schema::dropIfExists('daftar_lhp');
    }
}
