<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\LoginBackground;
use App\Support\Media;
use App\Support\MailSettings;
use App\Support\SettingSchema;
use App\Support\WhatsAppSender;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function edit(?string $tab = null)
    {
        $tabs = SettingSchema::tabs();
        $tab = array_key_exists((string) $tab, $tabs) ? $tab : array_key_first($tabs);

        return view('dashboard.settings.edit', compact('tabs', 'tab'));
    }

    public function update(Request $request, string $tab)
    {
        $tabs = SettingSchema::tabs();
        abort_unless(isset($tabs[$tab]), 404);

        // Tab Halaman Login punya isian khusus (foto, slide foto, video)
        if ($tab === 'login') {
            return $this->updateLogin($request);
        }

        $fields = $tabs[$tab]['fields'];
        $rules = [];
        foreach ($fields as $key => $field) {
            $rules[$key] = match ($field['type']) {
                'image'  => ['nullable', 'image'],
                'color'  => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
                'email'  => ['nullable', 'email', 'max:150'],
                'url'    => ['nullable', 'url', 'max:255'],
                'number' => ['nullable', 'integer'],
                'toggle' => ['nullable', 'boolean'],
                default  => ['nullable', 'string', 'max:3000'],
            };
        }
        $data = $request->validate($rules);

        $values = [];
        foreach ($fields as $key => $field) {
            $values[$key] = match ($field['type']) {
                'image'  => $request->hasFile($key) ? Upload::replace($request->file($key), setting($key), 'settings') : setting($key),
                'toggle' => $request->boolean($key) ? 1 : 0,
                default  => $data[$key] ?? '',
            };
        }

        Setting::putMany($values);
        activity_log('mengubah pengaturan', $tabs[$tab]['label']);

        return back()->with('success', 'Pengaturan '.$tabs[$tab]['label'].' disimpan.');
    }

    /** Kirim pesan percobaan agar pengaturan email / WhatsApp bisa dipastikan berfungsi. */
    public function testSend(Request $request, string $channel)
    {
        if ($channel === 'email') {
            $data = $request->validate(['to' => ['required', 'email']], [], ['to' => 'email tujuan']);

            if ($problem = MailSettings::problem()) {
                return back()->with('error', $problem);
            }

            try {
                MailSettings::apply();
                Mail::raw('Ini email percobaan dari website '.setting('site_name').'. Jika Anda membaca ini, pengaturan email sudah benar.', function ($m) use ($data) {
                    $m->to($data['to'])->subject('Tes email '.setting('site_name'));
                });
            } catch (\Throwable $e) {
                report($e);

                return back()->with('error', 'Email percobaan gagal: '.mb_substr($e->getMessage(), 0, 300));
            }

            return back()->with('success', 'Email percobaan terkirim ke '.$data['to'].'. Periksa kotak masuk (dan folder spam).');
        }

        $data = $request->validate(['to' => ['required', 'string', 'max:30']], [], ['to' => 'nomor WhatsApp tujuan']);
        [$ok, $error] = WhatsAppSender::send($data['to'], 'Ini pesan percobaan dari website '.setting('site_name').'. Jika Anda membaca ini, pengaturan WhatsApp sudah benar.');

        return $ok
            ? back()->with('success', 'WhatsApp percobaan terkirim ke '.$data['to'].'.')
            : back()->with('error', $error);
    }

    /**
     * Latar belakang halaman login: foto, slide foto, atau video.
     */
    protected function updateLogin(Request $request)
    {
        $data = $request->validate([
            'login_bg_type'   => ['required', Rule::in(array_keys(LoginBackground::TYPES))],
            'login_overlay'   => ['required', Rule::in(array_keys(LoginBackground::OVERLAYS))],
            // File tanpa batas ukuran & jumlah (file besar dikirim bertahap dari dashboard)
            'login_bg_image'  => ['nullable', 'image'],
            'remove_image'    => ['nullable', 'boolean'],
            'login_bg_video'  => ['nullable', 'file', 'mimes:'.Media::VIDEO_MIMES],
            'remove_video'    => ['nullable', 'boolean'],
            'slides'          => ['nullable', 'array'],
            'slides.*'        => ['image'],
            'remove_slides'   => ['nullable', 'array'],
            'remove_slides.*' => ['integer'],
        ], [
            'login_bg_video.mimes' => 'Video harus berformat MP4, WEBM, OGG, atau MOV agar bisa diputar di browser.',
        ], [
            'login_bg_image' => 'foto latar', 'login_bg_video' => 'video latar',
            'slides' => 'slide foto', 'slides.*' => 'slide foto', 'login_overlay' => 'lapisan warna',
        ]);

        // Foto (juga sampul sementara saat video sedang dimuat)
        $image = setting('login_bg_image') ?: null;
        if ($request->boolean('remove_image')) {
            Upload::delete($image);
            $image = null;
        }
        $image = Upload::replace($request->file('login_bg_image'), $image, 'settings/login');

        // Video
        $video = setting('login_bg_video') ?: null;
        if ($request->boolean('remove_video')) {
            Upload::delete($video);
            $video = null;
        }
        $video = Upload::replace($request->file('login_bg_video'), $video, 'settings/login');

        // Slide foto: hapus yang dicentang, lalu tambahkan yang baru
        $slides = collect(LoginBackground::slides());
        foreach ((array) $request->input('remove_slides', []) as $index) {
            Upload::delete($slides->get((int) $index));
            $slides->forget((int) $index);
        }
        foreach ((array) $request->file('slides', []) as $file) {
            if ($path = Upload::store($file, 'settings/login')) {
                $slides->push($path);
            }
        }
        $slides = $slides->values();

        $files = [
            'login_bg_image'  => $image ?? '',
            'login_bg_video'  => $video ?? '',
            'login_bg_slides' => $slides->all(),
        ];

        // Jenis yang dipilih harus punya isi
        $missing = match ($data['login_bg_type']) {
            'video'  => ! $video ? 'Unggah file video terlebih dahulu untuk latar jenis Video.' : null,
            'slider' => $slides->count() < 2 ? 'Slide foto membutuhkan minimal 2 gambar.' : null,
            default  => null,
        };
        if ($missing) {
            Setting::putMany($files);   // file yang sudah terunggah tetap disimpan

            return back()->with('error', $missing);
        }

        Setting::putMany($files + [
            'login_bg_type' => $data['login_bg_type'],
            'login_overlay' => $data['login_overlay'],
        ]);

        activity_log('mengubah latar halaman login', LoginBackground::TYPES[$data['login_bg_type']]['label']);

        return back()->with('success', 'Latar halaman login disimpan ('.LoginBackground::TYPES[$data['login_bg_type']]['label'].').');
    }

    /**
     * Pratinjau halaman login untuk Master Admin
     * (halaman /login biasa tidak bisa dibuka saat sedang masuk).
     */
    public function loginPreview()
    {
        return view('auth.login', ['preview' => true]);
    }
}
