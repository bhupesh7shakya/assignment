<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Artist extends Model
{
    use HasFactory;
    protected $fillable=[
        "name",
        "dob",
        "gender",
        "first_release_year",
        "user_id"
    ];

    public function music() {
        return $this->hasMany(Music::class);
    }

    public function user() {
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

    public function getGenderAttribute(){
        $gender=[
            'm'=>"Male",
            'f'=>"Female",
            'o'=>"Other"
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
        $tableName = (new static())->getTable();
        $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));

        $sql = "UPDATE $tableName SET $setClause WHERE id = ?";

        return DB::update($sql, array_merge(array_values($data), [$id]));
    }
}
