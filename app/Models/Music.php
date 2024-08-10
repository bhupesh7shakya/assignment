<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
    public $fillable= [
        "artist_id",
        "genre_id",
        "title",
        "album_name"
    ];
    public $with=[
        "genre",
        "artist"
    ];

    public function genre(){
        return $this->belongsTo(Genre::class);
    }

    public function artist(){
        return $this->belongsTo(Artist::class);
    }
}
