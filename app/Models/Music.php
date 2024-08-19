<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Music extends Model
{
    use HasFactory;
    public $fillable = [
        "artist_id",
        "genre_id",
        "title",
        "album_id"
    ];
    public $with = [
        "genre",
        "artist"
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public static function getAllRecords()
    {
        // Get the table name
        $tableName = (new self())->getTable();

        // Build the base SQL query
        $sql = "SELECT * FROM $tableName WHERE artist_id = ?";

        // Bind parameters
        $bindings = [Session::get('artist_id')];

        // Add user filter if necessary
        if (Auth::user()->role != "super_admin") {
            $sql .= " AND user_id = ?";
            $bindings[] = Auth::user()->id;
        }

        // Execute raw SQL query
        return DB::select($sql, $bindings);
    }



    public static function getAllRecordOfOnlyArtist($artist_id)
    {
        // Get the table name
        $tableName = (new self())->getTable();

        // Execute raw SQL query
        $results = DB::select("SELECT * FROM $tableName where artist_id = $artist_id");
        return $results;
        // Convert results to a collection of User model instances
    }


    public static function getAllRecordOfUserAndArtist($user_id, $artist_id)
    {
        // Get the table name
        $tableName = (new self())->getTable();

        // Execute raw SQL query
        $results = DB::select("SELECT * FROM $tableName where user_id = $user_id and artist_id=$artist_id");

        // Convert results to a collection of User model instances
        return self::hydrate($results);
    }

    public static function getRecords()
    {
        $model = new self();
        $tableName = $model->getTable();

        if (Session::has('artist_id')) {
            // Case when artist_id is in session
            $artistId = Session::get('artist_id');

            // Build SQL query with joins
            $sql = "
                SELECT $tableName.*,
                       artists.name AS artist_name,
                       albums.title AS album_title,
                       genres.name AS genre_name
                FROM $tableName
                LEFT JOIN artists ON $tableName.artist_id = artists.id
                LEFT JOIN albums ON $tableName.album_id = albums.id
                LEFT JOIN genres ON $tableName.genre_id = genres.id
                WHERE $tableName.artist_id = ?
            ";

            $bindings = [$artistId];

            if (Auth::user()->role != "super_admin") {
                $sql .= " AND $tableName.user_id = ?";
                $bindings[] = Auth::user()->id;
            }

            $results = DB::select($sql, $bindings);
        } else {
            // Case when artist_id is not in session
            if (Auth::user()->role != "super_admin") {
                $userId = Auth::id();

                // Get the IDs of artists associated with the authenticated user
                $artistIds = Artist::where('user_id', $userId)->pluck('id')->toArray();

                // Combine the user ID and artist IDs into a single array
                $ids = array_merge([$userId], $artistIds);

                // Build SQL query with joins
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $sql = "
                    SELECT $tableName.*,
                           artists.name AS artist_name,
                           albums.name AS album_name,
                           genres.name AS genre_name
                    FROM $tableName
                    LEFT JOIN artists ON $tableName.artist_id = artists.id
                    LEFT JOIN albums ON $tableName.album_id = albums.id
                    LEFT JOIN genres ON $tableName.genre_id = genres.id
                    WHERE $tableName.artist_id IN ($placeholders)
                ";

                $bindings = $ids;

                $results = DB::select($sql, $bindings);
            } else {
                // For super_admin or when no artist_id is in session
                $sql = "
                    SELECT $tableName.*,
                           artists.name AS artist_name,
                           albums.name AS album_name,
                           genres.name AS genre_name
                    FROM $tableName
                    LEFT JOIN artists ON $tableName.artist_id = artists.id
                    LEFT JOIN albums ON $tableName.album_id = albums.id
                    LEFT JOIN genres ON $tableName.genre_id = genres.id
                ";

                $results = DB::select($sql);
            }
        }

        // Hydrate results into model instances
        return self::hydrate($results);
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
