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
        Schema::create('history_penghuni_rumah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('rumah_id');
            $table->string('penghuni_id');
            $table->date('Tanggal_Mulai')->nullable();
            $table->date('Tanggal_Selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_penghuni_rumah');
    }
};
