<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Media tambahan untuk isi halaman (Struktur & Konten):
 * - blocks      : pilihan media (foto / video lokal / YouTube / PDF), file video, dan dokumen (PDF, Word, Excel, PowerPoint)
 * - block_items : video lokal dan dokumen untuk kartu di blok Koleksi Kartu (mis. PDF Year Book per angkatan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->string('media_type', 20)->nullable()->after('image_position'); // image | video | youtube | pdf | upload
            $table->string('video_file')->nullable()->after('video_url');
            $table->string('document')->nullable()->after('video_file');
            $table->string('document_name')->nullable()->after('document');
            $table->unsignedBigInteger('document_size')->nullable()->after('document_name');
        });

        Schema::table('block_items', function (Blueprint $table) {
            $table->string('video_file')->nullable()->after('video_url');
            $table->string('document')->nullable()->after('video_file');
            $table->string('document_name')->nullable()->after('document');
            $table->unsignedBigInteger('document_size')->nullable()->after('document_name');
            $table->boolean('document_download')->default(true)->after('document_size');
        });
    }

    public function down(): void
    {
        Schema::table('block_items', fn (Blueprint $table) => $table->dropColumn(['video_file', 'document', 'document_name', 'document_size', 'document_download']));
        Schema::table('blocks', fn (Blueprint $table) => $table->dropColumn(['media_type', 'video_file', 'document', 'document_name', 'document_size']));
    }
};
