<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMonevReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_rekomendasi', function (Blueprint $table) {
            $table->text('review_monev')->nullable();
            $table->text('review_spi')->nullable();
            $table->integer('publish_pic_1')->nullable()->default(0);
            $table->integer('publish_pic_2')->nullable()->default(0);
            $table->integer('draft')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_rekomendasi', function (Blueprint $table) {
            $table->dropColumn('review_monev');
            $table->dropColumn('review_spi');
            $table->dropColumn('draft');
            $table->dropColumn('publish_pic_1');
            $table->dropColumn('publish_pic_2');
        });
    }
}
