<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['gedung', 'asrama'])->default('gedung');
            $table->enum('level', ['umum', 'tk', 'sd', 'smp', 'sma'])->default('umum');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('label')->nullable();            // Ruang Kelas, Laboratorium, Kamar Putra
            $table->text('description')->nullable();
            $table->longText('detail')->nullable();
            $table->string('image')->nullable();
            $table->json('photos')->nullable();
            $table->string('capacity')->nullable();
            $table->string('area')->nullable();
            $table->string('used_for')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
