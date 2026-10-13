<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Data menu buatan admin di database sekolah.
 *
 * 1. menus.is_custom  : menandai Menu Utama / Submenu yang dibuat admin lewat dashboard (bukan menu bawaan).
 *    menus.created_by : admin yang membuat menu tersebut.
 * 2. menu_contents    : satu baris untuk setiap isi halaman menu buatan admin, lengkap dengan
 *    nama Menu Utama, Submenu, dan Halaman-nya, sehingga isi tiap menu mudah dicari langsung
 *    di database (mis. lewat phpMyAdmin) dan di dashboard (Data Menu Baru).
 *    Tabel ini diperbarui otomatis setiap kali admin menyimpan menu, blok, atau kartu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false)->after('access_pendaftaran');
            $table->foreignId('created_by')->nullable()->after('is_custom')->constrained('users')->nullOnDelete();
        });

        // Menu yang sudah dibuat admin sebelum update ini: dibuat lebih dari 2 menit setelah data awal (seeder)
        $seededAt = DB::table('menus')->min('created_at');
        if ($seededAt) {
            DB::table('menus')
                ->where('created_at', '>', Carbon::parse($seededAt)->addMinutes(2))
                ->update(['is_custom' => true, 'created_by' => DB::raw('updated_by')]);
        }

        Schema::create('menu_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->string('main_menu');                         // nama Menu Utama
            $table->string('submenu')->nullable();               // nama Submenu (kosong bila halaman langsung di Menu Utama)
            $table->string('page_title');                        // nama halaman
            $table->string('page_type', 60);                     // jenis halaman, mis. Halaman Konten, Berita, Galeri
            $table->string('page_url')->nullable();              // alamat halaman di website
            $table->unsignedInteger('position')->default(0);     // urutan isi di halaman (0 = keterangan halaman)
            $table->string('content_type', 80);                  // mis. Keterangan halaman, Teks, Dokumen / PDF, Kartu
            $table->string('content_title')->nullable();         // judul isi
            $table->longText('content_text')->nullable();        // isi tulisan (tanpa kode HTML)
            $table->text('link')->nullable();                    // link YouTube, tombol, atau tautan
            $table->json('files')->nullable();                   // file yang dipakai: [{"name": .., "path": ..}]
            $table->text('file_names')->nullable();              // nama file, dipisah koma (mudah dibaca)
            $table->boolean('is_active')->default(true);         // tampil di website atau disembunyikan
            $table->string('source', 20);                        // asal data: menu | block | card
            $table->unsignedBigInteger('source_id');             // id di tabel menus / blocks / block_items
            $table->string('created_by')->nullable();            // nama admin pembuat menu
            $table->string('updated_by')->nullable();            // nama admin yang terakhir mengubah
            $table->timestamp('created_at')->nullable();         // waktu isi dibuat
            $table->timestamp('updated_at')->nullable();         // waktu isi terakhir diubah
            $table->timestamp('synced_at')->nullable();          // waktu baris ini terakhir diperbarui

            $table->index(['main_menu', 'submenu']);
            $table->index(['source', 'source_id']);
            $table->index(['menu_id', 'position']);
        });

        // Isi awal dari menu buatan admin yang sudah ada
        try {
            \App\Support\MenuArchive::rebuild();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_contents');

        Schema::table('menus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn('is_custom');
        });
    }
};