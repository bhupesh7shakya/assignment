<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "user_id"
    ];
    public function music()
    {
        return $this->hasMany(Music::class);
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

    public static function getAllRecordWithMusicCount($userId = null)
    {
        // Get the table names
        $albumsTable = (new self())->getTable();
        $musicsTable = (new Music())->getTable(); // Assuming the table name for musics is 'musics'

        // Build the base SQL query
        $query = "
            SELECT
                $albumsTable.id,
                $albumsTable.name,
                $albumsTable.user_id,
                $albumsTable.created_at,
                $albumsTable.updated_at,
                COALESCE(COUNT($musicsTable.id), 0) AS music_count
            FROM
                $albumsTable
            LEFT JOIN
                $musicsTable
            ON
                $albumsTable.id = $musicsTable.album_id
            GROUP BY
                $albumsTable.id,
                $albumsTable.name,
                $albumsTable.user_id,
                $albumsTable.created_at,
                $albumsTable.updated_at
        ";

        // Add user filter if $userId is provided
        $bindings = [];
        if ($userId !== null) {
            $query .= " HAVING $albumsTable.user_id = ?";
            $bindings[] = $userId;
        }

        // Execute the query
        $results = DB::select($query, $bindings);

        // Convert results to a collection of Album model instances
        return collect($results)->map(function ($item) {
            $album = new self((array)$item);
            $album->id = $item->id;
            $album->music_count = $item->music_count;
            return $album;
        });
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
        // dd($data);
        $data['user_id'] = (Auth::user()->role == "super_admin") ? $data['user_id'] : Auth::user()->id;

        if (Album::find($id)->name == $data['name'] && Album::find($id)->user_id == $data['user_id']) {
            return true;
        }
        $tableName = (new static())->getTable();
        $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));

        $sql = "UPDATE $tableName SET $setClause WHERE id = ?";
        // dd($sql,$data['name'],$id);

        return DB::update($sql, array_merge(array_values($data), [$id]));
    }


    public static function getById($id)
    {
        // Get the table name of the model
        $tableName = (new static())->getTable();

        $sql = "SELECT * FROM $tableName WHERE id = ?";

        $result = DB::select($sql, [$id]);

        return !empty($result) ? $result[0] : null;
    }
}
