<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seat';
    protected $primaryKey = 'Seat_ID';
    public $timestamps = false;
    protected $guarded = [];
    public function getPriceAttribute(): int
    {
        if (str_contains($this->Seat_Type ?? '', '350')) return 350;
        if (str_contains($this->Seat_Type ?? '', '240')) return 240;
        return 180;
    }

    // ที่นั่งนี้ อยู่ในโรงไหน
    public function theater()
    {
        return $this->belongsTo(Theater::class, 'Theater_ID', 'Theater_ID');
    }
}
