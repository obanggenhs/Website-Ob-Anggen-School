<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    public const CATEGORIES = [
        'pimpinan'       => 'Pimpinan',
        'guru_sd'        => 'Guru SD',
        'guru_smp'       => 'Guru SMP',
        'guru_sma'       => 'Guru SMA',
        'pembina_asrama' => 'Pembina Asrama',
        'staf'           => 'Staf & Tata Usaha',
    ];

    protected $fillable = [
        'branch_id', 'name', 'slug', 'photo', 'category', 'position_title', 'subject', 'education',
        'email', 'phone', 'joined_year', 'about', 'duties', 'is_featured', 'position', 'is_active',
    ];

    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getPhotoUrlAttribute(): string
    {
        return media_url($this->photo, asset('assets/img/seed/teacher.jpg'));
    }
}
