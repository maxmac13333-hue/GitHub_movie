<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'Movie';
    protected $primaryKey = 'Movie_ID';
    public $timestamps = false;

    // 1 หนัง มีหลายรอบฉาย
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'Movie_ID', 'Movie_ID');
    }
}