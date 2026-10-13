<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'regency', 'description', 'image', 'position', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('position');
    }
}
