<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationNotification extends Model
{
    public const CHANNELS = ['email' => 'Email', 'whatsapp' => 'WhatsApp'];

    public const EVENTS = ['status' => 'Perubahan status', 'jadwal' => 'Jadwal', 'ulang' => 'Kirim ulang', 'daftar' => 'Konfirmasi pendaftaran', 'panitia' => 'Notifikasi ke panitia'];

    protected $fillable = [
        'registration_id', 'user_id', 'channel', 'event', 'target', 'status', 'message', 'error',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isSent(): bool
    {
        return $this->status === 'terkirim';
    }

    public function getEventLabelAttribute(): string
    {
        return self::EVENTS[$this->event] ?? $this->event;
    }

    public function getChannelLabelAttribute(): string
    {
        return self::CHANNELS[$this->channel] ?? ucfirst($this->channel);
    }
}
