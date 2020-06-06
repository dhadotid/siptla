<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLHPDeliverTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_lhp', function (Blueprint $table) {
            $table->integer('deliver_to')->nullable()->default(0)
            ->comment('0 = Belum sampai senior | 1 = Sampai senior belum sampai unit kerja | 2 = sampai unit kerja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_lhp', function (Blueprint $table) {
            $table->dropColumn('deliver_to');
        });
    }
}
