<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKolomTindakLanjut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->text('tindak_lanjut')->nullable();
            $table->text('action_plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tindak_lanjut_temuan', function (Blueprint $table) {
            $table->dropColumn('tindak_lanjut');
            $table->dropColumn('action_plan');
        });
    }
}
