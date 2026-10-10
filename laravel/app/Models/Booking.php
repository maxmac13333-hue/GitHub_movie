<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'Booking_ID';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['payment_expires_at'=>'datetime','paid_at'=>'datetime'];
    public function getPaymentLabelAttribute(): string {
        if ($this->payment_state==='expired') return 'หมดเวลาชำระเงิน';
        if ($this->Status==='ยกเลิก') return 'ยกเลิกแล้ว';
        return match($this->payment_state){'pending'=>'รอชำระเงิน (จำลอง)','paid'=>'ชำระแล้ว (จำลอง)',default=>'การจองเดิม · ชำระที่เคาน์เตอร์'};
    }

    // การจองนี้ ของใคร (เชื่อมกับตาราง users ของ Laravel)
    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'id');
    }

    // การจองนี้ เป็นของรอบฉายไหน
    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'Show_ID', 'Show_ID');
    }

    // 1 การจอง มีหลายตั๋ว/ที่นั่ง
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'Booking_ID', 'Booking_ID');
    }
}

