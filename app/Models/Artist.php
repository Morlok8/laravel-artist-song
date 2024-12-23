<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Artist extends Model
{
    //
    use HasFactory; 
    protected $table = "artists";
    protected $fillable = ["name"];

    public function songs(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
