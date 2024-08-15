<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


}
