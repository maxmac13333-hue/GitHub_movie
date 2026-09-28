<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seat';
    protected $primaryKey = 'Seat_ID';
    public $timestamps = false;

    // ที่นั่งนี้ อยู่ในโรงไหน
    public function theater()
    {
        return $this->belongsTo(Theater::class, 'Theater_ID', 'Theater_ID');
    }
}