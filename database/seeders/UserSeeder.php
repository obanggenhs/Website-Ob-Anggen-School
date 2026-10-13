<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $master = User::updateOrCreate(['email' => 'master@obanggen.sch.id'], [
            'name' => 'Master Admin', 'role' => 'master', 'password' => 'password123', 'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'publikasi@obanggen.sch.id'], [
            'name' => 'Admin Publikasi', 'role' => 'publikasi', 'password' => 'password123', 'is_active' => true, 'created_by' => $master->id,
        ]);

        User::updateOrCreate(['email' => 'pendaftaran@obanggen.sch.id'], [
            'name' => 'Admin Pendaftaran', 'role' => 'pendaftaran', 'password' => 'password123', 'is_active' => true, 'created_by' => $master->id,
        ]);
    }
}
