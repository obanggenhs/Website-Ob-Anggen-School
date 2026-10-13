<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Jalur pendaftaran, mis. Siswa SD, Siswa SMA, Rekrutmen Guru.
 */
class RegistrationType extends Model
{
    public const AUDIENCES = ['siswa' => 'Siswa', 'staf' => 'Guru & Staf'];

    protected $fillable = [
        'name', 'slug', 'audience', 'level_label', 'academic_year', 'image', 'summary', 'description',
        'requirements', 'steps', 'accepted_message', 'open_at', 'close_at', 'quota', 'is_open', 'position',
    ];

    protected $casts = ['open_at' => 'date', 'close_at' => 'date', 'is_open' => 'boolean'];

    public function fields()
    {
        return $this->hasMany(RegistrationField::class)->orderBy('position');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /** Dibuka bila sakelar aktif, dalam rentang tanggal, dan kuota belum penuh. */
    public function isAccepting(): bool
    {
        if (! $this->is_open) {
            return false;
        }
        if ($this->open_at && today()->lt($this->open_at)) {
            return false;
        }
        if ($this->close_at && today()->gt($this->close_at)) {
            return false;
        }
        if ($this->quota && $this->registrations()->where('status', '!=', 'ditolak')->count() >= $this->quota) {
            return false;
        }

        return true;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isAccepting()) {
            return 'Dibuka';
        }

        return $this->is_open && $this->open_at && today()->lt($this->open_at) ? 'Segera dibuka' : 'Ditutup';
    }

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image, asset('assets/img/seed/students.jpg'));
    }

    public function getPeriodLabelAttribute(): ?string
    {
        if (! $this->open_at && ! $this->close_at) {
            return null;
        }

        return ($this->open_at ? tgl($this->open_at, 'd M Y') : '…').' – '.($this->close_at ? tgl($this->close_at, 'd M Y') : '…');
    }
}
