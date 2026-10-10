<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movie';
    protected $primaryKey = 'Movie_ID';
    public $timestamps = false;
    protected $guarded = [];
    public function getPosterUrlAttribute(): string
    {
        return asset('movies_png/'.($this->Poster && is_file(public_path('movies_png/'.$this->Poster)) ? $this->Poster : 'placeholder.svg'));
    }

    // 1 หนัง มีหลายรอบฉาย
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'Movie_ID', 'Movie_ID');
    }
}
