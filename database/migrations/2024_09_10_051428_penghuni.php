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
        Schema::create('penghuni', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('Nama_Lengkap');
            $table->string('Foto_KTP')->nullable();
            $table->enum('Status_Penghuni', ['Kontrak', 'Tetap']);
            $table->string('Nomor_Telepon')->nullable();
            $table->enum('Status_Menikah', ['Ya', 'Tidak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghuni');
    }
};
