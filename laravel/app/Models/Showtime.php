<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $table = 'showtime';
    protected $primaryKey = 'Show_ID';
    public $timestamps = false;
    protected $guarded = [];
    public function tickets() { return $this->hasMany(Ticket::class, 'Show_ID', 'Show_ID'); }
    public function bookings() { return $this->hasMany(Booking::class, 'Show_ID', 'Show_ID'); }

    // รอบฉายนี้ เป็นของหนังเรื่องไหน
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'Movie_ID', 'Movie_ID');
    }

    // รอบฉายนี้ อยู่ที่โรงไหน
    public function theater()
    {
        return $this->belongsTo(Theater::class, 'Theater_ID', 'Theater_ID');
    }
}
