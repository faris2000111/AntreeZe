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
        Schema::create('pelayanan', function (Blueprint $table) {
            $table->bigIncrements('id_pelayanan');
            $table->unsignedBigInteger('id_admin'); 
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');    
            $table->string('jenis')->nullable();
            $table->integer('no_pelayanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan');
    }
};
