<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('id_booking');
            $table->string('nomor_booking')->nullable();
            $table->integer('no_pelayanan')->nullable();
            $table->string('id_users')->nullable();
            $table->text('alamat')->nullable();
            $table->string('id_layanan')->nullable();
            $table->time('jam_booking')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
