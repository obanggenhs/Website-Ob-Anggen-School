<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $fillable = ['category_id', 'user_id', 'title', 'slug', 'description', 'cover', 'video_url', 'event_date', 'is_active'];

    protected $casts = ['event_date' => 'date', 'is_active' => 'boolean'];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('position')->orderBy('id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCoverUrlAttribute(): string
    {
        $path = $this->cover ?: $this->photos->first()?->image;

        return $path ? media_url($path) : (youtube_thumb($this->video_url) ?? media_url(null));
    }
}
