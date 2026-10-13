<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tampilan blok:
 * - align       : perataan isi/paragraf, sekarang juga bisa "right" (rata kanan)
 * - title_align : perataan judul blok (left | center | right). Kosong = mengikuti perataan isi.
 * - placement   : posisi blok di halaman bila lebarnya tidak penuh (left | center | right)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->string('align', 10)->default('left')->change();
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->string('title_align', 10)->nullable()->after('align');
            $table->string('placement', 10)->default('left')->after('width');
        });
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn(['title_align', 'placement']);
        });

        DB::table('blocks')->whereNotIn('align', ['left', 'center', 'justify'])->update(['align' => 'left']);

        Schema::table('blocks', function (Blueprint $table) {
            $table->enum('align', ['left', 'center', 'justify'])->default('left')->change();
        });
    }
};