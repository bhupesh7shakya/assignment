<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'first_name' => 'The Beatles',
                'last_name' => 'Beatles',
                'name' => 'The Beatles',
                'address' => 'Somewhere',
                'gender' => 'm',
                'phone' => '9960622925',
                'email' => 'thebeatles@gmail.com',
                'password' => 'password', // Consider using password_hash() for security.
                'first_release_year' => '1963-2-1',
                'dob' => '1960-07-07',
                'albums' => [
                    [
                        'name' => 'Please Please Me',
                        'songs' => [
                            ['title'=>'I Saw Her Standing There'],
                            ['title'=>'Misery'],
                            ['title'=>'Anna (Go to Him)']
                        ]
                    ],
                    [
                        'name' => 'With The Beatles',
                        'songs' => [
                            ['title'=>'It Won’t Be Long'],
                            ['title'=>'All I’ve Got to Do'],
                            ['title'=>'All My Loving']
                        ]
                    ],
                    [
                        'name' => 'A Hard Day\'s Night',
                        'songs' => [
                            ['title'=>'A Hard Day’s Night'],
                            ['title'=>'I Should Have Known Better'],
                            ['title'=>'If I Fell']
                        ]
                    ],
                ]
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Jackson',
                'name' => 'Michael Jackson',
                'address' => 'Neverland',
                'gender' => 'm',
                'phone' => '9960622926',
                'email' => 'michaeljackson@gmail.com',
                'password' => 'password', // Consider using password_hash() for security.
                'first_release_year' => '1972-2-1',
                'dob' => '1958-08-29',
                'albums' => [
                    [
                        'name' => 'Thriller',
                        'songs' => [
                            ['title'=>'Thriller'],
                            ['title'=>'Beat It'],
                            ['title'=>'Billie Jean']
                        ]
                    ],
                    [
                        'name' => 'Bad',
                        'songs' => [
                            ['title'=>'Bad'],
                            ['title'=>'Smooth Criminal'],
                            ['title'=>'The Way You Make Me Feel']
                        ]
                    ],
                    [
                        'name' => 'Dangerous',
                        'songs' => [
                            ['title'=>'Black or White'],
                            ['title'=>'Remember the Time'],
                            ['title'=>'Heal the World']
                        ]
                    ],
                ]
            ],
            [
                'first_name' => 'Elvis',
                'last_name' => 'Presley',
                'name' => 'Elvis Presley',
                'address' => 'Graceland',
                'gender' => 'm',
                'phone' => '9960622927',
                'email' => 'elvispresley@gmail.com',
                'password' => 'password', // Consider using password_hash() for security.
                'first_release_year' => '1956-2-1',
                'dob' => '1935-01-08',
                'albums' => [
                    [
                        'name' => 'Elvis Presley',
                        'songs' => [
                            ['title'=>'Blue Suede Shoes'],
                            ['title'=>'I Got a Woman'],
                            ['title'=>'Heartbreak Hotel']
                        ]
                    ],
                    [
                        'name' => 'Elvis',
                        'songs' => [
                            ['title'=>'Rip It Up'],
                            ['title'=>'Love Me'],
                            ['title'=>'When My Blue Moon Turns to Gold Again']
                        ]
                    ],
                    [
                        'name' => 'From Elvis in Memphis',
                        'songs' => [
                            ['title'=>'In the Ghetto'],
                            ['title'=>'Suspicious Minds'],
                            ['title'=>'Any Day Now']
                        ]
                    ],
                ]
            ],
            [
                'first_name' => 'Madonna',
                'last_name' => '',
                'name' => 'Madonna',
                'address' => 'Bay City, Michigan',
                'gender' => 'f',
                'phone' => '9960622928',
                'email' => 'madonna@gmail.com',
                'password' => 'password', // Consider using password_hash() for security.
                'first_release_year' => '1983-2-1',
                'dob' => '1958-08-16',
                'albums' => [
                    [
                        'name' => 'Like a Virgin',
                        'songs' => [
                            ['title' => 'Like a Virgin'],
                            ['title' => 'Material Girl'],
                            [
                                'title' => 'Love Don\'t Live Here Anymore'
                            ]
                        ]
                    ],
                    [
                        'name' => 'True Blue',
                        'songs' => [
                            ['title' => 'Papa Don\'t Preach'],
                            ['title' => 'Open Your Heart'],
                            [
                                'title' => 'Live to Tell'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Ray of Light',
                        'songs' => [
                            ['title' => 'Frozen'],
                            ['title' => 'Ray of Light'],
                            [
                                'title' => 'The Power of Good-Bye'
                            ]
                        ]
                    ],
                ]
            ],
            [
                'first_name' => 'Elton',
                'last_name' => 'John',
                'name' => 'Elton John',
                'address' => 'Pinner, Middlesex',
                'gender' => 'm',
                'phone' => '9960622929',
                'email' => 'eltonjohn@gmail.com',
                'password' => 'password', // Consider using password_hash() for security.
                'first_release_year' => '1969-2-1',
                'dob' => '1947-03-25',
                'albums' => [
                    [
                        'name' => 'Goodbye Yellow Brick Road',
                        'songs' => [
                            ['title' => 'Goodbye Yellow Brick Road'],
                            ['title' => 'Candle in the Wind'],
                            [
                                'title' => 'Bennie and the Jets'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Honky Château',
                        'songs' => [
                            ['title' => 'Rocket Man'],
                            ['title' => 'Honky Cat'],
                            [
                                'title' => 'Mona Lisas and Mad Hatters'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Captain Fantastic and the Brown Dirt Cowboy',
                        'songs' => [
                            ['title' => 'Someone Saved My Life Tonight'],
                            ['title' => 'Meal Ticket'],
                            [
                                'title' => 'Writing'
                            ]
                        ]
                    ],
                ]
            ]
        ];

        // Insert the data into the artists table
        foreach ($artists as $artist) {

            $fields = ['first_name', 'last_name', 'email', 'password', 'address', 'dob', 'phone'];
            $user = [];

            foreach ($fields as $field) {
                if (isset($artist[$field])) {
                    $user[$field] = $artist[$field];
                    unset($artist[$field]);
                }
            }
            $user['password']=bcrypt($user['password']);
            $user['role']="artist";
            $user = User::firstOrCreate(['email' => $user['email']], $user);
            $albums = $artist['albums'];
            unset($artist['albums']);

            $artist['user_id'] = $user->id;
            $artist['dob'] = $user->dob;
            $artist = Artist::firstOrCreate(['name' => $artist['name']], $artist);

            foreach ($albums as $a) {
                $a['user_id'] = $artist->id;
                $music = $a['songs'];
                unset($a['songs']);
                $a = Album::firstOrCreate(['name' => $a['name']], $a);
                foreach ($music as $m) {
                    // dd($m);
                    $m['album_id'] = $a->id;
                    $m['artist_id'] = $artist->id;
                    $m['genre_id']=Genre::inRandomOrder()->first()->id;
                    Music::firstOrCreate(['title'=>$m['title']],$m);
                }
            }

            // $music = $albums['songs'];
            // unset($albums['songs']);
            // print_r($albums);

            // print_r($music);

            // $album['user_id']=$artist->id;

            // $album = Album::firstOrCreate(['title' => $album['name'], 'artist_id' => $artist->id], $album);

            // $album['album_id']=$album->id;
            // $album['user_id']=$user->id;
            // Music::firstOrCreate(
            //     ['title' => $album['name'], 'album_id' => $album->id],
            //     $album
            // );
        }
    }
}
