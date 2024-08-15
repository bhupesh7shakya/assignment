<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
                'first_name' => 'super',
                'last_name' => 'user',
                'email' => 'superuser@gmail.com',
                'gender' => 'm',
                'address' => '123 Main St',
                'dob' => '1990-01-01',
                'phone' => '1234567890',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // password hashing
                'role' => 'super_admin',
            ],
            [
                'first_name' => 'Artist',
                'last_name' => 'manager',
                'email' => 'manager@gmail.com',
                'gender' => 'f',
                'address' => '456 Elm St',
                'dob' => '1992-02-02',
                'phone' => '0987654321',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // password hashing
                'role' => 'artist_manager',
            ],
            [
                'first_name' => 'Artist',
                'last_name' => 'Singer',
                'email' => 'artist@gmail.com',
                'gender' => 'f',
                'address' => '789 Oak St',
                'dob' => '1995-03-03',
                'phone' => '1122334455',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // password hashing
                'role' => 'artist',
            ]
        ]);
        $user=User::find(3);
        Artist::create(
            [
                'first_name' => 'Artist',
                'last_name' => 'Singer',
                'dob' => '1995-03-03',
                'user_id'=>$user->id,
                "first_release_year"=>'1995-03-03',

            ]
            );
    }
}
