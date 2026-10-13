<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Susunan blok terhadap blok sebelumnya:
 * auto  = otomatis (mengikuti lebar & posisi)
 * right = bersebelahan, di kanan blok sebelumnya
 * left  = bersebelahan, di depan (kiri) blok sebelumnya
 * below = atas-bawah, mulai baris baru di bawah blok sebelumnya
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->string('pairing', 10)->default('auto')->after('placement');
        });
    }

    public function down(): void
    {
        Schema::table('blocks', fn (Blueprint $table) => $table->dropColumn('pairing'));
    }
};
