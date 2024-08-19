<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Artist extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "dob",
        "gender",
        "first_release_year",
        "user_id"
    ];

    public function music()
    {
        return $this->hasMany(Music::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function getAllRecord()
    {
        // Get the table name
        $tableName = (new self())->getTable();

        // Execute raw SQL query
        $results = DB::select("SELECT * FROM $tableName");

        // Convert results to a collection of User model instances
        return self::hydrate($results);
    }

    public function getGenderAttribute()
    {
        $gender = [
            'm' => "Male",
            'f' => "Female",
            'o' => "Other"
        ];
        $genderCode = $this->attributes['gender'];

        // Return the full text representation or a default value if code is not found
        return $gender[$genderCode] ?? 'Unknown';
    }

    public static function deleteById($id)
    {
        $model = new self();
        $tableName = $model->getTable();

        // Build SQL query with joins for deletion
        $sql = "DELETE FROM $tableName WHERE id = ?";
        // Execute raw SQL query
        return  DB::delete($sql, [$id]);
    }

    public static function insertRaw(array $data)
    {
        $tableName = (new static())->getTable();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

        return DB::insert($sql, array_values($data));
    }

    public static function updateRaw($id, array $data)
    {
        $remove=[
            "first_name",
            "last_name",
            "password",
            "email",
            "address",
            "phone"
        ];
        $new_data=[];
        foreach($remove as $r){
            $new_data[$r]=$data[$r];
        }
        foreach($remove as $k) {
            unset($data[$k]);
        }
        // dd($id);
        $user_id=self::getById($id)->user_id;
        // dd($user_id);
        User::updateRaw($user_id,$new_data);
        $tableName = (new static())->getTable();
        $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));

        $sql = "UPDATE $tableName SET $setClause WHERE id = ?";

        return DB::update($sql, array_merge(array_values($data), [$id]));
    }

    public static function count_artist()
    {
        // Get the table name of the model
        $tableName = (new static())->getTable();

        // Build the SQL query to count the number of records
        $sql = "SELECT COUNT(id) as count FROM $tableName";

        // Execute the raw SQL query
        $results = DB::select($sql);

        // Extract the count from the result
        return $results[0]->count ?? 0;
    }
    public static function getArtistWithMusicCount()
    {
        $tableName = (new self())->getTable();

        // Define the raw SQL query
        $sql = "
        SELECT $tableName.name, COUNT(music.id) as music_count
        FROM $tableName
        LEFT JOIN music ON $tableName.id = music.genre_id
        GROUP BY $tableName.id ,
        $tableName.name
        ORDER BY music_count DESC
        limit 5
    ";

        // Execute the raw SQL query
        $results = DB::select($sql);

        return $results;
    }
    public static function getById($id) {
        // Get the table name of the model
        $tableName = (new static())->getTable();

        $sql = "SELECT $tableName.*,users.id as user_id FROM $tableName Join users ON $tableName.user_id=users.id WHERE $tableName.id = ?";

        $result = DB::select($sql, [$id]);
        // dd($id);
        return !empty($result) ? $result[0] : null;
    }

}
