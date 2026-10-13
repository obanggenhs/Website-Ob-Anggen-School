<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur website: Menu Utama (parent_id null) dan Submenu/Fitur (punya parent).
 * Setiap baris punya "type" yang menentukan isi kontennya (halaman, berita, galeri, dst).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type', 30)->default('halaman');
            $table->string('param', 60)->nullable();        // filter modul, mis. jenjang kalender "sd"
            $table->string('url')->nullable();              // khusus tipe tautan
            $table->string('icon', 60)->nullable();

            // Header halaman di website
            $table->string('subtitle')->nullable();
            $table->string('banner')->nullable();
            $table->longText('intro')->nullable();          // teks pembuka di atas isi modul
            $table->boolean('show_sidebar')->default(true);

            // Tampilan di navbar
            $table->boolean('is_active')->default(true);
            $table->boolean('is_highlight')->default(false);
            $table->boolean('open_new_tab')->default(false);
            $table->unsignedInteger('position')->default(0);

            // Hak kelola di dashboard role lain (diatur Master Admin)
            $table->boolean('access_publikasi')->default(false);
            $table->boolean('access_pendaftaran')->default(false);

            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['parent_id', 'position']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
