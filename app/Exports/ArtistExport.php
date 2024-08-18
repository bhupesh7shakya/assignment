<?php

namespace App\Exports;

use App\Models\Artist;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArtistExport implements FromCollection, WithHeadings, WithMapping{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Artist::with('user')->get();
    }

    public function headings(): array
    {
        return [
            "name",
            "dob",
            "gender",
            "first_release_year",
            "user_id",
            "first_name",
            "last_name",
            "email",
            "address",
            "dob",
            "phone",


        ];
    }



    public function map($artist): array
    {
        // Access related user data safely
        $user = $artist->user;

        return [
            $artist->name,
            $artist->dob,
            $artist->gender,
            $artist->first_release_year,
            $user->id ?? 'N/A',
            $user->first_name ?? 'N/A',
            $user->last_name ?? 'N/A',
            $user->email ?? 'N/A',
            $user->address ?? 'N/A',
            $user->dob ?? 'N/A',
            $user->phone ?? 'N/A',
        ];
    }
}
