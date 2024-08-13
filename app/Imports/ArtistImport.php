<?php

namespace App\Imports;

use App\Models\Artist;
use Maatwebsite\Excel\Concerns\ToModel;

class ArtistImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new Artist([
            "name"=>$row[1],
            "dob"=>$row[2],
            "gender"=>$row[3],
            "first_release_year"=>$row[4],
            "no_of_albums_released"=>$row[5]
        ]);
    }
}
