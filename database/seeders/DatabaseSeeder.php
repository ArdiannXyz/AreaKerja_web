<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Includes: SuperAdmin, Admin, Finance, Perusahaan, Pelamar, Kandidat, dan TipsKerja.
     */
    public function run(): void
    {
        $this->call(UserTestSeeder::class);
    }
}
