<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel yang dikelola Admin Pendaftaran: jalur pendaftaran, formulir, data pendaftar, riwayat proses.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('audience', ['siswa', 'staf'])->default('siswa');
            $table->string('level_label')->nullable();      // SD, SMP, SMA, Guru, Pembina Asrama
            $table->string('academic_year', 9)->nullable();
            $table->string('image')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->text('requirements')->nullable();       // 1 baris = 1 dokumen
            $table->text('steps')->nullable();              // 1 baris = 1 tahap
            $table->text('accepted_message')->nullable();   // pesan bila diterima
            $table->date('open_at')->nullable();
            $table->date('close_at')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->boolean('is_open')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('registration_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_type_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('name', 60);
            $table->enum('type', ['text', 'email', 'tel', 'number', 'date', 'textarea', 'select', 'radio', 'file']);
            $table->enum('section', ['data_diri', 'orang_tua', 'dokumen', 'lainnya'])->default('data_diri');
            $table->text('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedTinyInteger('width')->default(6);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['registration_type_id', 'name']);
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_type_id')->constrained()->cascadeOnDelete();
            $table->string('number', 30)->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 30);
            $table->json('data')->nullable();
            $table->json('files')->nullable();
            $table->string('status', 20)->default('baru');
            $table->dateTime('schedule_at')->nullable();
            $table->string('schedule_place')->nullable();
            $table->text('public_note')->nullable();        // terlihat oleh pendaftar
            $table->text('internal_note')->nullable();      // hanya untuk admin
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('registration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 20)->nullable();
            $table->string('title');
            $table->text('note')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_logs');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('registration_fields');
        Schema::dropIfExists('registration_types');
    }
};
