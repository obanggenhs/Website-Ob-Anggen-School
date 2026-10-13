<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uniqueness extends Model
{
    protected $fillable = ['title', 'slug', 'icon', 'excerpt', 'content', 'image', 'photos', 'video_url', 'position', 'is_active'];

    protected $casts = ['photos' => 'array', 'is_active' => 'boolean'];

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image);
    }
}
