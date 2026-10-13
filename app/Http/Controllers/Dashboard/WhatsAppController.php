<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\WhatsAppSender;
use Illuminate\Http\Request;

/**
 * Hubungkan gateway WhatsApp dari dashboard Pendaftaran
 * (bisa dibuka Master Admin maupun Admin Pendaftaran). Isian yang sama juga ada di
 * Pengaturan Website › Email & WhatsApp — keduanya menyimpan ke pengaturan yang sama.
 */
class WhatsAppController extends Controller
{
    public function edit()
    {
        return view('dashboard.whatsapp.edit', [
            'connected' => WhatsAppSender::enabled() && WhatsAppSender::configured(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'wa_api_url'   => ['required', 'url', 'max:255'],
            'wa_token'     => ['nullable', 'string', 'max:500'],
            'reg_notify_wa' => ['nullable', 'string', 'max:30'],
        ], [], ['wa_api_url' => 'alamat API', 'wa_token' => 'token', 'reg_notify_wa' => 'nomor WhatsApp panitia']);

        $values = [
            'wa_api_url'    => trim($data['wa_api_url']),
            'reg_notify_wa' => trim((string) ($data['reg_notify_wa'] ?? '')),
            'reg_send_wa'   => $request->boolean('reg_send_wa') ? 1 : 0,
        ];

        // Token dikosongkan di formulir = tetap memakai token yang sudah tersimpan
        if (filled($data['wa_token'] ?? null)) {
            $values['wa_token'] = trim($data['wa_token']);
        }

        Setting::putMany($values);
        activity_log('mengubah koneksi WhatsApp', $values['wa_api_url']);

        if (! WhatsAppSender::configured()) {
            return back()->with('error', 'Tersimpan, tetapi Token belum diisi sehingga WhatsApp belum terhubung.');
        }

        return back()->with('success', 'Gateway WhatsApp tersimpan. Kirim pesan tes di bawah untuk memastikan sudah terhubung.');
    }

    public function test(Request $request)
    {
        $data = $request->validate(['to' => ['required', 'string', 'max:30']], [], ['to' => 'nomor WhatsApp tujuan']);

        [$ok, $error] = WhatsAppSender::send($data['to'], 'Ini pesan percobaan dari website '.setting('site_name').'. Jika Anda membaca ini, WhatsApp sudah terhubung.');

        return $ok
            ? back()->with('success', 'WhatsApp percobaan terkirim ke '.$data['to'].'. Koneksi berhasil.')
            : back()->with('error', $error);
    }
}
