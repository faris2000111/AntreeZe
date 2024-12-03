<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->string('nama_pembeli');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('phone_token')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->string('device_token')->nullable();
            $table->boolean('verified_user')->default(false);
            $table->string('avatar')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
