<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lapisan warna di atas latar header halaman & hero Beranda: tipis / sedang / pekat.
 * Kosong (null) berarti mengikuti pengaturan umum semua halaman (settings: page_header_overlay).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('banner_overlay', 10)->nullable()->after('banner_slides');
        });
    }

    public function down(): void
    {
        Schema::table('menus', fn (Blueprint $table) => $table->dropColumn('banner_overlay'));
    }
};