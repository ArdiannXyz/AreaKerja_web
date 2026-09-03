<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean 12 tables data.
     */
    public function run(): void
    {
        $this->call(UserTestSeeder::class);
        $this->call(EventDummySeeder::class);
    }
}
