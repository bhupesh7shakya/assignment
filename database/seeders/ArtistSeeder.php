<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $artists = [
            [
                'name' => 'The Beatles',
                'dob' => '1960-07-07',
                'gender' => 'm',
                'first_release_year' => '1963-03-22',

            ],
            [
                'name' => 'Madonna',
                'dob' => '1958-08-16',
                'gender' => 'f',
                'first_release_year' => '1983-07-27',

            ],
            [
                'name' => 'Elvis Presley',
                'dob' => '1935-01-08',
                'gender' => 'm',
                'first_release_year' => '1956-03-23',

            ],
            [
                'name' => 'David Bowie',
                'dob' => '1947-01-08',
                'gender' => 'm',
                'first_release_year' => '1967-06-01',

            ],
            [
                'name' => 'Lady Gaga',
                'dob' => '1986-03-28',
                'gender' => 'f',
                'first_release_year' => '2008-08-19',

            ],
        ];

        // Insert the data into the artists table
        foreach ($artists as $artist) {
            Artist::create($artist);
        }
    }
}
