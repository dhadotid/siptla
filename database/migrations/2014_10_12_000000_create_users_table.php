<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *  1. administrator
     *  2. operator
     *  3. admin opd
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telepon')->nullable();
            $table->string('level')->nullable()->default(0);
            $table->integer('flag')->nullable()->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
