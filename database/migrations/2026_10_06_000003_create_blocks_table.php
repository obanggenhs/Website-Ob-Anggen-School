<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Blok isi halaman (teks, gambar + teks, video, daftar, galeri, kutipan, kartu, tombol).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30)->default('teks');
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('video_url')->nullable();
            $table->text('items')->nullable();              // 1 baris = 1 item
            $table->string('caption')->nullable();
            $table->string('button_label')->nullable();
            $table->string('button_url')->nullable();
            $table->enum('image_position', ['left', 'right'])->default('right');
            $table->unsignedTinyInteger('width')->default(12);
            $table->enum('style', ['plain', 'card', 'accent'])->default('plain');
            $table->enum('align', ['left', 'center', 'justify'])->default('left');
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['menu_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
