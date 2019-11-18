<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnStatusAndParafFromTindakLanjutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('paraf_inspektorat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tindak_lanjut', function (Blueprint $table) {
            $table->string('status')->after('detail_id');
            $table->integer('paraf_inspektorat')->after('tindak_lanjut');
        });
    }
}
