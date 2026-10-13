<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Instalasi sekali jalan: php artisan oa:install
 * Membuat database (bila belum ada), tabel, data awal, dan link storage.
 */
Artisan::command('oa:install {--fresh : Hapus semua tabel lalu buat ulang}', function () {
    $this->info('Instalasi website Ob Anggen School');

    $database = config('database.connections.mysql.database');

    try {
        DB::connection()->getPdo();
    } catch (\Throwable $e) {
        // Database belum ada → buat dulu lewat koneksi tanpa nama database
        config(['database.connections.mysql_setup' => array_merge(config('database.connections.mysql'), ['database' => null])]);
        try {
            DB::connection('mysql_setup')->statement("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            DB::purge('mysql');
            $this->line("Database `{$database}` dibuat.");
        } catch (\Throwable $e2) {
            $this->error('Tidak bisa terhubung ke MySQL: '.$e2->getMessage());
            $this->line('Pastikan MySQL menyala dan pengaturan DB_* di file .env sudah benar.');

            return 1;
        }
    }

    $this->call($this->option('fresh') ? 'migrate:fresh' : 'migrate', ['--force' => true, '--seed' => true]);

    if (! file_exists(public_path('storage'))) {
        $this->callSilent('storage:link');
    }

    $this->call('optimize:clear');

    $this->newLine();
    $this->info('Selesai! Jalankan: php artisan serve');
    $this->table(['Role', 'Email', 'Kata sandi'], [
        ['Master Admin', 'master@obanggen.sch.id', 'password123'],
        ['Admin Publikasi', 'publikasi@obanggen.sch.id', 'password123'],
        ['Admin Pendaftaran', 'pendaftaran@obanggen.sch.id', 'password123'],
    ]);

    return 0;
})->purpose('Pasang database dan data awal website Ob Anggen School');
