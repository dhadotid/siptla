<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNavigateToMappingNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rekomendasi_mapping_notification', function (Blueprint $table) {
            $table->string('navigate')->nullable();
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
            $table->dropColumn('navigate');
        });
    }
}
