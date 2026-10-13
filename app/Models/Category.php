<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const TYPES = ['news' => 'Berita', 'gallery' => 'Galeri'];

    protected $fillable = ['name', 'slug', 'type'];

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function albums()
    {
        return $this->hasMany(GalleryAlbum::class);
    }
}
