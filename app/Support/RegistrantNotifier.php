<?php

namespace App\Support;

use App\Mail\RegistrationReceived;
use App\Mail\RegistrationStatusChanged;
use App\Models\Registration;
use App\Models\RegistrationNotification;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * Mengirim kabar status pendaftaran ke pendaftar lewat email dan WhatsApp
 * (memakai email & nomor yang mereka isi saat mendaftar), lalu mencatat hasilnya.
 * Kegagalan kirim TIDAK pernah membatalkan penyimpanan status.
 */
class RegistrantNotifier
{
    /**
     * @return RegistrationNotification[]  hasil per saluran yang dicoba
     */
    public static function send(Registration $registration, ?string $note, ?User $by = null, bool $email = true, bool $whatsapp = true, string $event = 'status'): array
    {
        $registration->loadMissing('type');
        $results = [];

        if ($email && setting('reg_send_mail')) {
            $results[] = self::sendEmail($registration, $note, $by, $event);
        }
        if ($whatsapp && WhatsAppSender::enabled()) {
            $results[] = self::sendWhatsApp($registration, $note, $by, $event);
        }

        return $results;
    }

    protected static function sendEmail(Registration $registration, ?string $note, ?User $by, string $event): RegistrationNotification
    {
        return self::deliverMail($registration, $by, $event, $registration->email, new RegistrationStatusChanged($registration, $note),
            'Status pendaftaran '.$registration->number.': '.$registration->status_label.($note ? "\n".$note : ''));
    }

    protected static function deliverMail(Registration $registration, ?User $by, string $event, ?string $to, Mailable $mailable, string $summary): RegistrationNotification
    {
        $error = MailSettings::problem();

        if (! $error) {
            if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
                $error = 'Alamat email tujuan tidak valid: "'.$to.'".';
            } else {
                try {
                    Mail::to($to)->send($mailable);
                } catch (\Throwable $e) {
                    report($e);
                    $error = 'Email gagal dikirim: '.mb_substr($e->getMessage(), 0, 300);
                }
            }
        }

        return self::record($registration, $by, 'email', $event, $to, $error, $summary);
    }

    /**
     * Saat ada yang mendaftar: email + WhatsApp ke panitia, konfirmasi email + WhatsApp ke pendaftar.
     *
     * @return RegistrationNotification[]
     */
    public static function registrationReceived(Registration $registration): array
    {
        $registration->loadMissing('type');
        $results = [];
        $site = setting('site_name');

        // --- ke panitia / email resmi sekolah
        $committeeMail = setting('reg_notify_email') ?: setting('contact_email');
        if ($committeeMail) {
            $results[] = self::deliverMail($registration, null, 'panitia', $committeeMail, new RegistrationReceived($registration, true),
                'Pendaftar baru '.$registration->full_name.' ('.$registration->number.')');
        }

        if (WhatsAppSender::enabled() && filled(setting('reg_notify_wa'))) {
            $text = '*'.$site."*\n*Pendaftar baru!*\n\n"
                .'Nama: '.$registration->full_name."\n"
                .'Nomor: '.$registration->number."\n"
                .'Jalur: '.($registration->type->name ?? '-')."\n"
                .'HP/WA: '.$registration->phone."\n"
                .'Email: '.$registration->email."\n\n"
                .'Proses di dashboard: '.route('dashboard.applicants.show', $registration);
            [$ok, $error] = WhatsAppSender::send(setting('reg_notify_wa'), $text);
            $results[] = self::record($registration, null, 'whatsapp', 'panitia', WhatsAppSender::normalize(setting('reg_notify_wa')) ?: setting('reg_notify_wa'), $ok ? null : $error, $text);
        }

        // --- konfirmasi ke pendaftar
        if (setting('reg_send_mail')) {
            $results[] = self::deliverMail($registration, null, 'daftar', $registration->email, new RegistrationReceived($registration),
                'Pendaftaran diterima '.$registration->number);
        }

        if (WhatsAppSender::enabled()) {
            $text = '*'.$site."*\n\nHalo ".$registration->full_name.",\nformulir pendaftaran Anda sudah kami terima.\n"
                .'Nomor pendaftaran: *'.$registration->number."*\nJalur: ".($registration->type->name ?? '-')."\n\n"
                .'Pantau status seleksi di: '.route('registration.status', ['nomor' => $registration->number, 'email' => $registration->email]);
            [$ok, $error] = WhatsAppSender::send($registration->phone, $text);
            $results[] = self::record($registration, null, 'whatsapp', 'daftar', WhatsAppSender::normalize($registration->phone) ?: $registration->phone, $ok ? null : $error, $text);
        }

        return $results;
    }

    protected static function sendWhatsApp(Registration $registration, ?string $note, ?User $by, string $event): RegistrationNotification
    {
        $message = self::whatsAppText($registration, $note);
        [$ok, $error] = WhatsAppSender::send($registration->phone, $message);

        return self::record($registration, $by, 'whatsapp', $event,
            WhatsAppSender::normalize($registration->phone) ?: $registration->phone, $ok ? null : $error, $message);
    }

    /** Isi pesan WhatsApp untuk pendaftar. */
    public static function whatsAppText(Registration $registration, ?string $note): string
    {
        $info = $registration->status_info;
        $text = '*'.setting('site_name')."*\n"
            .'Panitia Penerimaan Pendaftar'."\n\n"
            .'Halo '.$registration->full_name.",\n"
            .'Status pendaftaran Anda ('.$registration->number.' · '.($registration->type->name ?? '-').") sekarang:\n"
            .'*'.$registration->status_label."*\n"
            .$info['public'];

        if ($note) {
            $text .= "\n\n*Pesan dari panitia:*\n".$note;
        }
        if ($registration->status === 'seleksi' && $registration->schedule_at && ! str_contains((string) $note, $registration->schedule_place ?? '¤')) {
            $text .= "\n\n*Jadwal:* ".tgl($registration->schedule_at, 'l, d F Y').' pukul '.$registration->schedule_at->format('H.i').' WIT · '.$registration->schedule_place;
        }
        if ($registration->status === 'diterima' && ! empty($registration->type?->accepted_message)) {
            $text .= "\n\n".$registration->type->accepted_message;
        }

        return $text."\n\nCek detail: ".route('registration.status', ['nomor' => $registration->number, 'email' => $registration->email]);
    }

    protected static function record(Registration $registration, ?User $by, string $channel, string $event, ?string $target, ?string $error, ?string $message): RegistrationNotification
    {
        return RegistrationNotification::create([
            'registration_id' => $registration->id,
            'user_id'         => $by?->id,
            'channel'         => $channel,
            'event'           => $event,
            'target'          => $target,
            'status'          => $error ? 'gagal' : 'terkirim',
            'message'         => $message,
            'error'           => $error,
        ]);
    }

    /** Ringkasan hasil kirim untuk pesan di dashboard: [teks sukses, teks gagal]. */
    public static function summarize(array $results): array
    {
        $ok = $fail = [];
        foreach ($results as $r) {
            if ($r->isSent()) {
                $ok[] = $r->channel_label.' ke '.$r->target;
            } else {
                $fail[] = $r->channel_label.': '.$r->error;
            }
        }

        return [
            $ok ? 'Pesan terkirim otomatis lewat '.implode(' dan ', $ok).'.' : '',
            $fail ? 'Pesan belum terkirim — '.implode(' | ', $fail) : '',
        ];
    }
}
