<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('nomor_booking')->nullable();
            $table->integer('no_pelayanan')->nullable();
            $table->unsignedBigInteger('id_users')->nullable();
            $table->unsignedBigInteger('id_layanan')->nullable();
            $table->time('jam_booking')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('status')->nullable();
            $table->boolean('status_dilewati')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking');
    }
};
