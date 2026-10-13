<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    /** Tahapan proses pendaftaran (urut). */
    public const STATUSES = [
        'baru'       => ['label' => 'Baru masuk', 'color' => 'secondary', 'icon' => 'bi-inbox', 'public' => 'Data Anda sudah kami terima dan menunggu pemeriksaan panitia.'],
        'verifikasi' => ['label' => 'Berkas diverifikasi', 'color' => 'info', 'icon' => 'bi-patch-check', 'public' => 'Berkas Anda sudah diperiksa dan dinyatakan lengkap.'],
        'perbaikan'  => ['label' => 'Perlu perbaikan', 'color' => 'warning', 'icon' => 'bi-exclamation-triangle', 'public' => 'Ada data atau berkas yang perlu diperbaiki. Baca catatan panitia di bawah.'],
        'seleksi'    => ['label' => 'Tes / wawancara', 'color' => 'primary', 'icon' => 'bi-calendar-event', 'public' => 'Anda dijadwalkan mengikuti tes atau wawancara. Perhatikan jadwal di bawah.'],
        'diterima'   => ['label' => 'Diterima', 'color' => 'success', 'icon' => 'bi-trophy', 'public' => 'Selamat! Anda dinyatakan diterima.'],
        'ditolak'    => ['label' => 'Tidak diterima', 'color' => 'danger', 'icon' => 'bi-x-circle', 'public' => 'Mohon maaf, Anda belum dapat kami terima pada periode ini. Terima kasih atas minat Anda.'],
    ];

    /** Tahap yang tampil di garis proses (stepper) halaman cek status. */
    public const PIPELINE = ['baru', 'verifikasi', 'seleksi', 'hasil'];

    protected $fillable = [
        'registration_type_id', 'number', 'full_name', 'email', 'phone', 'data', 'files', 'status',
        'schedule_at', 'schedule_place', 'public_note', 'internal_note', 'handled_by', 'decided_at', 'ip_address',
    ];

    protected $casts = [
        'data'        => 'array',
        'files'       => 'array',
        'schedule_at' => 'datetime',
        'decided_at'  => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(RegistrationType::class, 'registration_type_id');
    }

    public function logs()
    {
        return $this->hasMany(RegistrationLog::class)->latest('id');
    }

    /** Riwayat pengiriman email / WhatsApp otomatis ke pendaftar. */
    public function notifications()
    {
        return $this->hasMany(RegistrationNotification::class)->latest('id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? self::STATUSES['baru'];
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status_info['label'];
    }

    public function isFinal(): bool
    {
        return in_array($this->status, ['diterima', 'ditolak'], true);
    }

    /** Posisi di stepper: 0 baru, 1 verifikasi, 2 seleksi, 3 hasil. */
    public function pipelineStep(): int
    {
        return match ($this->status) {
            'verifikasi', 'perbaikan' => 1,
            'seleksi' => 2,
            'diterima', 'ditolak' => 3,
            default => 0,
        };
    }

    /** Nomor otomatis: OA-2026-0001 */
    public static function generateNumber(): string
    {
        $prefix = strtoupper(setting('reg_prefix', 'OA')).'-'.date('Y').'-';
        $last = static::where('number', 'like', $prefix.'%')->orderByDesc('number')->value('number');
        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
