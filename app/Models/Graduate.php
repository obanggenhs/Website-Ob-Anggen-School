<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{
    public const LEVELS = ['sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'];

    protected $fillable = ['name', 'slug', 'photo', 'level', 'graduation_year', 'class_name', 'predicate', 'continue_to', 'testimony', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getPhotoUrlAttribute(): string
    {
        return media_url($this->photo, asset('assets/img/seed/graduate-boy.jpg'));
    }

    public function getLevelLabelAttribute(): string
    {
        return self::LEVELS[$this->level] ?? strtoupper($this->level);
    }
}
