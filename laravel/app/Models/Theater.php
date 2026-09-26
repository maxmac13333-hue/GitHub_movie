<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    protected $table = 'Theater';
    protected $primaryKey = 'Theater_ID';
    public $timestamps = false;

    // 1 โรง มีหลายที่นั่ง
    public function seats()
    {
        return $this->hasMany(Seat::class, 'Theater_ID', 'Theater_ID');
    }

    // 1 โรง มีหลายรอบฉาย
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'Theater_ID', 'Theater_ID');
    }
}