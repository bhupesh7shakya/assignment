<?php

namespace App\Imports;

use App\Models\Artist;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class ArtistImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function model(array $row)
    // {
    //     // dd($row);
    //     return new Artist([
    //         "name"=>$row[1],
    //         "dob"=>$row[2],
    //         "gender"=>$row[3],
    //         "first_release_year"=>$row[4],
    //         "no_of_albums_released"=>$row[5]
    //     ]);
    // }
    protected $rowCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;

        // Skip the first row
        if ($this->rowCount === 1) {
            return null;
        }

        // Ensure the row contains the expected number of columns
        if (count($row) < 10) {
            // Handle the case where there are not enough columns
            return null;
        }

        // Map the row based on index positions
        $name = $row[0] ?? null;
        $dob = $row[1] ?? null;
        $gender = $row[2] ?? null;
        $firstReleaseYear = $row[3] ?? null;
        $email = $row[4] ?? null;
        $firstName = $row[5] ?? null;
        $lastName = $row[6] ?? null;
        $address = $row[7] ?? null;
        $phone = $row[8] ?? null;

        if ($email === null) {
            // Handle missing email case if necessary
            return null;
        }

        // Find or create the user
        $user = User::updateOrCreate(
            ['email' => $email], // Unique identifier
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address' => $address,
                'dob' => $dob,
                'phone' => $phone,
                'password' => bcrypt('password'), // Use a default or generated password; hash it
            ]
        );

        // Create or update the artist
        return new Artist([
            'name' => $name,
            'dob' => $dob,
            'gender' => $gender,
            'first_release_year' => $firstReleaseYear,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            // Define validation rules here
            'name' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'first_release_year' => 'required|integer',
            'user_id' => 'nullable|exists:users,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'address' => 'nullable|string',
            'dob' => 'nullable|date',
            'phone' => 'nullable|string',
        ];
    }
}
