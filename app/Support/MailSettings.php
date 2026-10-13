<?php

namespace App\Support;

/**
 * Email memakai isian di Pengaturan Website > Email & WhatsApp (tanpa perlu mengubah file .env).
 * Bila isian itu kosong, Laravel tetap memakai MAIL_* dari file .env.
 */
class MailSettings
{
    /** Terapkan pengaturan SMTP dari dashboard ke konfigurasi Laravel. */
    public static function apply(): void
    {
        $user = trim((string) setting('mail_username'));
        $pass = str_replace(' ', '', (string) setting('mail_password'));   // sandi aplikasi Gmail sering ditulis berspasi

        if ($user === '' || $pass === '') {
            return;
        }

        $port = (int) (setting('mail_port') ?: 587);

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.transport'  => 'smtp',
            'mail.mailers.smtp.host'       => trim((string) setting('mail_host', 'smtp.gmail.com')),
            'mail.mailers.smtp.port'       => $port,
            'mail.mailers.smtp.scheme'     => $port === 465 ? 'smtps' : 'smtp',
            'mail.mailers.smtp.username'   => $user,
            'mail.mailers.smtp.password'   => $pass,
            'mail.mailers.smtp.timeout'    => 20,
            'mail.from.address'            => $user,
            'mail.from.name'               => setting('mail_from_name') ?: setting('site_name'),
        ]);
    }

    /** Alasan email pasti tidak akan sampai, atau null bila siap kirim. */
    public static function problem(): ?string
    {
        self::apply();

        $mailer = config('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            return 'Email belum diatur: isi "Email pengirim" dan "Sandi aplikasi" di Pengaturan Website › Email & WhatsApp, lalu simpan.';
        }

        if ($mailer === 'smtp') {
            $host = (string) config('mail.mailers.smtp.host');
            if (str_contains($host, 'gmail') && blank(config('mail.mailers.smtp.password'))) {
                return 'Sandi aplikasi Gmail belum diisi di Pengaturan Website › Email & WhatsApp.';
            }
        }

        return null;
    }
}
