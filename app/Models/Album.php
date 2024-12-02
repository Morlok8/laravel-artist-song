<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    //
    use HasFactory; 
    protected $table = "albums";
    protected $fillable = ["artist_id", "release_year"];

    public function songs()
    {
        return $this->morphToMany(Song::class, 'song_album');
    }
}
