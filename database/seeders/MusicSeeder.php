<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MusicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $artists = Artist::all();
        $genres = Genre::all();

        // Create multiple music records
        foreach ($artists as $artist) {
            $a=Album::create(
                [
                    'name'=>"Album ".fake()->name()
                ]
            );
            for ($i = 1; $i <= 5; $i++) {
                Music::create([
                    'artist_id' => $artist->id,
                    'title' => 'Song Title ' . $i . ' by ' . $artist->name,
                    'album_id' => $a->id,
                    'genre_id' => $genres->random()->id, // Assign random genre
                ]);
            }
        }
    }
}
