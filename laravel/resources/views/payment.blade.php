@if($booking->payment_state==='pending' && $booking->Status!=='ยกเลิก')
<section class="summary-panel" style="max-width:660px;margin:24px auto" x-data="{seconds:{{ max(0,now()->diffInSeconds($booking->payment_expires_at,false)) }},timer:null,deadline:0,init(){this.deadline=Date.now()+this.seconds*1000;this.timer=setInterval(()=>{this.seconds=Math.max(0,Math.ceil((this.deadline-Date.now())/1000));if(this.seconds===0){clearInterval(this.timer);location.reload()}},1000)},destroy(){clearInterval(this.timer)}}">
<span class="eyebrow">DEMO PAYMENT</span><h2>ชำระเงินจำลอง</h2><p class="notice">สำหรับทดลองระบบเท่านั้น ไม่ต้องโอนเงินจริง<br>เลือกรูปภาพตัวอย่างเป็นสลิป ระบบจะยืนยันการชำระจำลองทันที</p>
<div class="summary-total"><span>เวลาที่เหลือ</span><strong x-text="Math.floor(seconds/60).toString().padStart(2,'0')+':'+(seconds%60).toString().padStart(2,'0')"></strong></div><p class="muted small">หมดเวลาแล้วระบบจะยกเลิกและคืนที่นั่งให้ผู้อื่นจอง</p>
@if((int)$booking->User_ID===(int)auth()->id())
<form method="POST" enctype="multipart/form-data" action="{{ route('bookings.pay',$booking) }}" style="margin-top:20px;display:grid;gap:14px">@csrf<label>แนบรูปสลิปจำลอง<input style="display:block;margin-top:8px;width:100%" type="file" name="slip" accept="image/jpeg,image/png,image/webp" required></label><small class="muted">JPG, PNG หรือ WebP ไม่เกิน 2 MB</small><button class="btn" :disabled="seconds<=0">ยืนยันชำระเงินจำลอง · {{ number_format($booking->Total_Price) }} บาท</button></form>
@endif
</section>
@elseif($booking->payment_state==='paid' && $booking->Status!=='ยกเลิก')
<div class="notice success" style="max-width:660px;margin:24px auto">ชำระเงินจำลองสำเร็จ · ไม่มีการโอนเงินจริง <a class="text-link" href="{{ route('bookings.slip',$booking) }}" target="_blank" rel="noopener">ดูสลิปที่แนบ ↗</a></div>
@elseif($booking->payment_state==='expired')
<div class="notice error" style="max-width:660px;margin:24px auto">หมดเวลา 10 นาทีแล้ว ระบบคืนที่นั่งเรียบร้อย <a href="{{ route('movies.showtimes',$booking->showtime->Movie_ID) }}">เลือกรอบและจองใหม่ →</a></div>
@endif

