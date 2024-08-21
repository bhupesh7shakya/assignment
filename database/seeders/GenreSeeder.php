<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            ['name' => 'Rock'],
            ['name' => 'Pop'],
            ['name' => 'Jazz'],
            ['name' => 'Classical'],
            ['name' => 'Hip-Hop'],
            ['name' => 'Electronic'],
            ['name' => 'Reggae'],
            ['name' => 'Blues'],
            ['name' => 'Country'],
            ['name' => 'R&B'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
