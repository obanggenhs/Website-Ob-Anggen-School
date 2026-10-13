<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    /** Kategori agenda beserta warna penanda di kalender. */
    public const CATEGORIES = [
        'akademik' => ['label' => 'Akademik', 'color' => '#3B6FD8'],
        'ujian'    => ['label' => 'Ujian / Penilaian', 'color' => '#E08A1E'],
        'libur'    => ['label' => 'Hari Libur', 'color' => '#D64545'],
        'kegiatan' => ['label' => 'Kegiatan Sekolah', 'color' => '#2F9E6A'],
    ];

    public const LEVELS = ['semua' => 'Semua jenjang', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'];

    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'time_info', 'location', 'category', 'level', 'academic_year', 'is_active'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_active' => 'boolean'];

    public function scopeForLevel($q, ?string $level)
    {
        return $level && $level !== 'semua'
            ? $q->whereIn('level', [$level, 'semua'])
            : $q;
    }

    public function scopeUpcoming($q)
    {
        return $q->where(fn ($w) => $w->whereDate('start_date', '>=', today())->orWhereDate('end_date', '>=', today()))
            ->orderBy('start_date');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category]['label'] ?? $this->category;
    }

    public function getCategoryColorAttribute(): string
    {
        return self::CATEGORIES[$this->category]['color'] ?? '#888888';
    }

    public function getLevelLabelAttribute(): string
    {
        return self::LEVELS[$this->level] ?? $this->level;
    }

    public function getDateLabelAttribute(): string
    {
        if (! $this->end_date || $this->end_date->equalTo($this->start_date)) {
            return tgl($this->start_date, 'l, d F Y');
        }

        return $this->start_date->isSameMonth($this->end_date)
            ? $this->start_date->format('d').' – '.tgl($this->end_date, 'd F Y')
            : tgl($this->start_date, 'd M').' – '.tgl($this->end_date, 'd M Y');
    }

    public function isOngoing(): bool
    {
        $end = $this->end_date ?? $this->start_date;

        return today()->between($this->start_date, $end);
    }

    public function getRelativeLabelAttribute(): string
    {
        if ($this->isOngoing()) {
            return 'Berlangsung';
        }

        $days = (int) today()->diffInDays($this->start_date, false);

        return match (true) {
            $days === 1 => 'Besok',
            $days > 1 && $days <= 30 => $days.' hari lagi',
            $days < 0 => 'Selesai',
            default => tgl($this->start_date, 'M Y'),
        };
    }

    /** Data ringkas untuk kalender interaktif di website (dibaca oleh calendar.js). */
    public function toCalendarArray(): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'start'         => $this->start_date->format('Y-m-d'),
            'end'           => ($this->end_date ?? $this->start_date)->format('Y-m-d'),
            'category'      => $this->category,
            'categoryLabel' => $this->category_label,
            'color'         => $this->category_color,
            'level'         => $this->level_label,
            'dateLabel'     => $this->date_label,
            'time'          => $this->time_info,
            'location'      => $this->location,
            'description'   => $this->description,
        ];
    }
}