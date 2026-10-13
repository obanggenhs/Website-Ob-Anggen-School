<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    public const CHANNELS = [
        'umum'        => 'Berita umum',
        'pendaftaran' => 'Info pendaftaran',
    ];

    protected $fillable = [
        'category_id', 'user_id', 'channel', 'title', 'slug', 'cover', 'excerpt', 'body',
        'is_featured', 'status', 'published_at', 'views',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getCoverUrlAttribute(): string
    {
        return media_url($this->cover);
    }

    public function getSummaryAttribute(): string
    {
        return $this->excerpt ?: Str::limit(trim(strip_tags($this->body)), 150);
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }
}
