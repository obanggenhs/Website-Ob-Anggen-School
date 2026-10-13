<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 1. block_items  : isi blok "Koleksi Kartu". Setiap kartu punya halaman detail sendiri
 *                   (mis. daftar alumni, program, prestasi, mitra sekolah).
 * 2. blocks.settings : pengaturan tampilan blok (jumlah kolom, bentuk gambar, teks tombol).
 * 3. menus.page_options : fitur bantuan pengunjung per halaman
 *                   (pencarian, filter, urutkan, tampilan, jumlah per halaman, bagikan).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('block_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();          // keterangan singkat di bawah judul
            $table->string('category', 100)->nullable();     // dipakai filter kategori (mis. Angkatan 2020)
            $table->string('year', 10)->nullable();          // dipakai filter tahun
            $table->string('image')->nullable();
            $table->json('photos')->nullable();
            $table->string('video_url')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->text('meta')->nullable();                // "Label: Nilai" per baris, tampil di detail
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['block_id', 'position']);
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->json('settings')->nullable()->after('align');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->json('page_options')->nullable()->after('show_sidebar');
        });
    }

    public function down(): void
    {
        Schema::table('menus', fn (Blueprint $table) => $table->dropColumn('page_options'));
        Schema::table('blocks', fn (Blueprint $table) => $table->dropColumn('settings'));
        Schema::dropIfExists('block_items');
    }
};
