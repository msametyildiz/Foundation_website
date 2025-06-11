<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ...existing code...
        $this->call([
            ProjectSeeder::class,
        ]);
    }
}