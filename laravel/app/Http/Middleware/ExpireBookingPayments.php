<?php
namespace App\Http\Middleware;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
class ExpireBookingPayments {
 public function handle($request, \Closure $next){
  Booking::where('payment_state','pending')->where('payment_expires_at','<=',now())->pluck('Booking_ID')->each(function($id){
   DB::transaction(function()use($id){$booking=Booking::lockForUpdate()->find($id);if($booking && $booking->payment_state==='pending' && $booking->payment_expires_at->lte(now())){$booking->tickets()->delete();$booking->update(['payment_state'=>'expired','Status'=>'ยกเลิก']);}});
  });
  return $next($request);
 }
}
