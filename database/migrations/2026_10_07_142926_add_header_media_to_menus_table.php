<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Latar header setiap menu (dan hero Beranda) kini bisa berupa:
 *  - image   : satu foto (kolom banner yang sudah ada)
 *  - video   : file video dari komputer sendiri (mp4 / webm)
 *  - youtube : link video YouTube
 *  - slider  : beberapa foto yang bergantian (slide)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('banner_type', 20)->default('image')->after('banner');
            $table->string('banner_video')->nullable()->after('banner_type');
            $table->string('banner_youtube')->nullable()->after('banner_video');
            $table->json('banner_slides')->nullable()->after('banner_youtube');
        });

        // Gambar hero Beranda yang lama (tabel settings) dipindah ke menu Beranda agar tidak hilang
        $hero = DB::table('settings')->where('key', 'hero_image')->value('value');
        if ($hero) {
            DB::table('menus')->where('type', 'beranda')->whereNull('banner')->update(['banner' => $hero]);
        }
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['banner_type', 'banner_video', 'banner_youtube', 'banner_slides']);
        });
    }
};