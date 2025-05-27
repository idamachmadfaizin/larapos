<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'UMUM',
        ]);

        $this->call([
//            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
