<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRekomendasiMappingNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rekomendasi_mapping_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_lhp')->nullable()->default(0);
            $table->integer('id_temuan')->nullable()->default(0);
            $table->integer('id_rekomendasi')->nullable()->default(0);
            $table->integer('user_id')->nullable()->default(0);
            $table->integer('is_read')->nullable()->default(0)
            ->comment('0 = Belum di read | 1 = Sudah di read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rekomendasi_mapping_notification', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('id_lhp');
            $table->dropColumn('id_temuan');
            $table->dropColumn('id_rekomendasi');
            $table->dropColumn('user_id');
            $table->dropColumn('is_read');
        });
    }
}
