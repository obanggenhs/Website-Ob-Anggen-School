<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Satu isi halaman dari menu buatan admin (tabel menu_contents).
 * Diisi & diperbarui otomatis oleh App\Support\MenuArchive, jadi tidak diubah manual.
 */
class MenuContent extends Model
{
    public $timestamps = false;   // created_at / updated_at diambil dari waktu isi aslinya

    protected $fillable = [
        'menu_id', 'main_menu', 'submenu', 'page_title', 'page_type', 'page_url', 'position',
        'content_type', 'content_title', 'content_text', 'link', 'files', 'file_names', 'is_active',
        'source', 'source_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'synced_at',
    ];

    protected $casts = [
        'files'      => 'array',
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'synced_at'  => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /** Lokasi isi, mis. "Ekstrakurikuler › Pramuka". */
    public function getPathAttribute(): string
    {
        return $this->submenu ? $this->main_menu.' › '.$this->submenu : $this->main_menu;
    }
}