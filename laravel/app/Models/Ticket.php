<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';
    protected $primaryKey = 'Ticket_ID';
    public $timestamps = false;
    protected $guarded = [];

    public function booking() { return $this->belongsTo(Booking::class, 'Booking_ID', 'Booking_ID'); }
    public function seat() { return $this->belongsTo(Seat::class, 'Seat_ID', 'Seat_ID'); }
}
