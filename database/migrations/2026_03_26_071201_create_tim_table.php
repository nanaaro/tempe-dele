<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim', function (Blueprint $table) {
            $table->string('kode_tim', 50)->primary();
            $table->string('nama_tim', 255)->nullable();
            $table->string('nama_ketua', 255)->nullable();
            $table->string('niplama_ketua', 30)->nullable();
            $table->string('nipbaru_ketua', 30)->nullable();
            $table->integer('is_penugasan_khusus')->nullable();
            $table->string('status', 45)->nullable();
            $table->date('tanggal_non_aktif')->nullable();
            $table->integer('jumlah_anggota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim');
    }
};
