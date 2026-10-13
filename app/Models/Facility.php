<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public const TYPES = ['gedung' => 'Gedung Sekolah', 'asrama' => 'Asrama'];

    public const LEVELS = ['umum' => 'Umum', 'tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'];

    protected $fillable = [
        'branch_id', 'type', 'level', 'name', 'slug', 'label', 'description', 'detail', 'image', 'photos',
        'capacity', 'area', 'used_for', 'position', 'is_active',
    ];

    protected $casts = ['photos' => 'array', 'is_active' => 'boolean'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image);
    }

    public function getLevelLabelAttribute(): string
    {
        return self::LEVELS[$this->level] ?? $this->level;
    }
}
