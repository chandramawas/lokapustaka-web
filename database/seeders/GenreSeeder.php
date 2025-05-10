<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Genre::create(['name' => 'Thriller']);
        Genre::create(['name' => 'Sejarah']);
        Genre::create(['name' => 'Konspirasi']);
        Genre::create(['name' => 'Drama']);

        Genre::factory(10)->create();
    }
}
