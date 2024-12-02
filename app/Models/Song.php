<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Song extends Model
{
    //
    use HasFactory; 
    protected $table = "songs";
    protected $fillable = ["name"];

    public function songs(){
        return $this->morphedByMany(Post::class, 'song_album');
    }

    public function albums(){
        return $this->morphedByMany(Album::class, 'foreign_key');
    }
}
