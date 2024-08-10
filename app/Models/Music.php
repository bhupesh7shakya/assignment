<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
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
