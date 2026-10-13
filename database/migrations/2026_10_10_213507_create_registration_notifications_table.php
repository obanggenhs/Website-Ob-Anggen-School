<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catatan pengiriman pesan otomatis (email & WhatsApp) ke pendaftar.
 * Setiap kali admin menyimpan status / jadwal, hasil kirim per saluran dicatat di sini
 * supaya admin tahu pesan terkirim atau gagal (beserta alasannya).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel', 20);              // email | whatsapp
            $table->string('event', 20)->default('status');   // status | jadwal | ulang
            $table->string('target', 190)->nullable();  // alamat email / nomor WA tujuan
            $table->string('status', 20);               // terkirim | gagal
            $table->text('message')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['registration_id', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_notifications');
    }
};
