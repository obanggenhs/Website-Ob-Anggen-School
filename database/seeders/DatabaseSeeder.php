<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            ProfileSeeder::class,
            PublicationSeeder::class,
            FacilitySeeder::class,
            RegistrationSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
