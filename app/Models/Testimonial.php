<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'role', 'content', 'photo', 'show_on_home', 'position', 'is_active'];

    protected $casts = ['show_on_home' => 'boolean', 'is_active' => 'boolean'];

    public function getPhotoUrlAttribute(): string
    {
        return media_url($this->photo, asset('assets/img/seed/graduate-girl.jpg'));
    }
}
