<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static ?array $loaded = null;

    public static function allCached(): array
    {
        if (static::$loaded !== null) {
            return static::$loaded;
        }

        try {
            return static::$loaded = Cache::rememberForever('site.settings', fn () => static::pluck('value', 'key')->all());
        } catch (\Throwable $e) {
            return static::$loaded = [];   // database belum siap (mis. saat instalasi)
        }
    }

    public static function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => is_array($value) ? json_encode($value) : $value]);
        }

        static::flushCache();
    }

    public static function flushCache(): void
    {
        Cache::forget('site.settings');
        static::$loaded = null;
    }
}
