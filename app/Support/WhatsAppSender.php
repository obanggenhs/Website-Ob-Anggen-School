<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

/**
 * Mengirim pesan WhatsApp otomatis lewat layanan gateway (mis. Fonnte).
 *
 * Gateway menerima permintaan POST berisi: target (nomor), message (isi pesan), countryCode,
 * dengan header Authorization berisi token. Alamat API & token diisi Master Admin di
 * Pengaturan Website > Email & WhatsApp. Gateway lain yang memakai format sama juga bisa dipakai.
 */
class WhatsAppSender
{
    public static function enabled(): bool
    {
        return (bool) setting('reg_send_wa');
    }

    public static function configured(): bool
    {
        return filled(setting('wa_api_url')) && filled(setting('wa_token'));
    }

    /** 0812-3456-789 / +62 812 3456 789 / 812... → 628123456789 (null jika tidak masuk akal). */
    public static function normalize(?string $phone): ?string
    {
        $number = preg_replace('/\D/', '', (string) $phone);

        if (str_starts_with($number, '00')) {
            $number = substr($number, 2);
        }
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62'.$number;
        }

        return strlen($number) >= 9 && strlen($number) <= 15 ? $number : null;
    }

    /**
     * @return array{0: bool, 1: ?string}  [berhasil?, pesan kesalahan]
     */
    public static function send(?string $phone, string $message): array
    {
        $target = self::normalize($phone);
        if (! $target) {
            return [false, 'Nomor WhatsApp tujuan tidak valid: "'.$phone.'".'];
        }
        if (! self::configured()) {
            return [false, 'Gateway WhatsApp belum diisi. Hubungkan di Dashboard › Pendaftaran › Koneksi WhatsApp (atau Pengaturan Website › Email & WhatsApp).'];
        }

        try {
            $response = Http::withHeaders(['Authorization' => (string) setting('wa_token')])
                ->timeout(20)
                ->asForm()
                ->post((string) setting('wa_api_url'), [
                    'target'      => $target,
                    'message'     => $message,
                    'countryCode' => '62',
                ]);
        } catch (\Throwable $e) {
            report($e);

            return [false, 'Gagal menghubungi gateway WhatsApp: '.$e->getMessage()];
        }

        $json = $response->json();
        $apiOk = $response->successful() && (! is_array($json) || ($json['status'] ?? true) !== false);

        if ($apiOk) {
            return [true, null];
        }

        $reason = is_array($json)
            ? ($json['reason'] ?? $json['detail'] ?? $json['message'] ?? null)
            : null;

        return [false, 'Gateway menolak pesan (HTTP '.$response->status().'): '.($reason ?: mb_substr(strip_tags($response->body()), 0, 200))];
    }
}
