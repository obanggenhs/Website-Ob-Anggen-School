<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Support\SettingSchema;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SettingSchema::defaults() as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::flushCache();
    }
}
