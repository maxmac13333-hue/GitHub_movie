<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function checkout(Request $request, Showtime $showtime)
    {
        $data = $request->validate(['seats' => ['required', 'array', 'min:1', 'max:8'], 'seats.*' => ['required', 'integer', 'distinct']]);
        if (strtotime($showtime->Show_Date.' '.$showtime->Show_Time) <= time()) {
            throw ValidationException::withMessages(['seats' => 'รอบฉายนี้ผ่านไปแล้ว']);
        }
        $seats = Seat::where('Theater_ID', $showtime->Theater_ID)->whereIn('Seat_ID', $data['seats'])->orderBy('Seat_No')->get();
        if ($seats->count() !== count($data['seats'])) throw ValidationException::withMessages(['seats' => 'ที่นั่งไม่ถูกต้อง']);
        if (Ticket::where('Show_ID', $showtime->Show_ID)->whereIn('Seat_ID', $data['seats'])->exists()) {
            throw ValidationException::withMessages(['seats' => 'มีที่นั่งถูกจองไปแล้ว กรุณาเลือกใหม่']);
        }
        $showtime->load(['movie', 'theater']);
        return view('checkout', compact('showtime', 'seats'));
    }

    public function store(Request $request, Showtime $showtime)
    {
        try {
        $data = $request->validate(['seats' => ['required', 'array', 'min:1', 'max:8'], 'seats.*' => ['required', 'integer', 'distinct']]);
        if (strtotime($showtime->Show_Date.' '.$showtime->Show_Time) <= time()) {
            throw ValidationException::withMessages(['seats' => 'รอบฉายนี้ผ่านไปแล้ว']);
        }
        $booking = DB::transaction(function () use ($request, $showtime, $data) {
            $seats = Seat::where('Theater_ID', $showtime->Theater_ID)->whereIn('Seat_ID', $data['seats'])->orderBy('Seat_ID')->lockForUpdate()->get();
            if ($seats->count() !== count($data['seats'])) throw ValidationException::withMessages(['seats' => 'ที่นั่งไม่ถูกต้อง']);
            if (Ticket::where('Show_ID', $showtime->Show_ID)->whereIn('Seat_ID', $data['seats'])
                ->whereHas('booking', fn ($q) => $q->where('Status', '!=', 'ยกเลิก'))->exists()) {
                throw ValidationException::withMessages(['seats' => 'มีที่นั่งถูกจองไปแล้ว กรุณาเลือกใหม่']);
            }
            $booking = Booking::create([
                'User_ID' => $request->user()->id, 'Show_ID' => $showtime->Show_ID,
                'payment_state' => 'pending', 'payment_expires_at' => now()->addMinutes(10), 'Booking_Date' => now(), 'Total_Price' => $seats->sum('price'), 'Status' => 'ปกติ',
                'Seat_Summary' => $seats->pluck('Seat_No')->join(', '),
            ]);
            foreach ($seats as $seat) Ticket::create([
                'Booking_ID' => $booking->Booking_ID, 'Show_ID' => $showtime->Show_ID,
                'Seat_ID' => $seat->Seat_ID, 'Ticket_Code' => 'TK-'.Str::upper(Str::random(10)),
            ]);
            return $booking;
        });
        } catch (ValidationException $exception) {
            return redirect()->route('showtimes.seats', $showtime)->withErrors($exception->errors());
        }
        return redirect()->route('bookings.show', $booking)->with('status', 'สำรองที่นั่งแล้ว กรุณาชำระเงินจำลองและแนบสลิปภายใน 10 นาที');
    }

    public function pay(Request $request, Booking $booking)
    {
        abort_unless((int)$booking->User_ID === (int)$request->user()->id, 403);
        $request->validate(['slip'=>['required','image','mimes:jpg,jpeg,png,webp','max:2048']],['slip.required'=>'กรุณาแนบรูปสลิปจำลอง','slip.image'=>'กรุณาเลือกไฟล์รูปภาพ','slip.max'=>'ขนาดรูปต้องไม่เกิน 2 MB']);
        return DB::transaction(function()use($request,$booking){
            $locked=Booking::lockForUpdate()->findOrFail($booking->Booking_ID);
            if($locked->payment_state!=='pending' || $locked->Status==='ยกเลิก' || $locked->payment_expires_at->lte(now())) return back()->withErrors(['slip'=>'รายการนี้หมดเวลาหรือไม่สามารถชำระได้แล้ว']);
            $path=$request->file('slip')->store('payment-slips','local');
            if(!$path) throw new \RuntimeException('Unable to save payment slip');
            $locked->update(['payment_state'=>'paid','paid_at'=>now(),'payment_slip'=>$path]);
            return redirect()->route('bookings.show',$locked)->with('status','ชำระเงินจำลองสำเร็จ รับบัตรการจองได้แล้ว');
        });
    }
    public function slip(Request $request, Booking $booking)
    {
        abort_unless((int)$booking->User_ID===(int)$request->user()->id || $request->user()->is_admin,403);
        abort_unless($booking->payment_slip && \Illuminate\Support\Facades\Storage::disk('local')->exists($booking->payment_slip),404);
        return response()->file(\Illuminate\Support\Facades\Storage::disk('local')->path($booking->payment_slip),['Cache-Control'=>'private, no-store','X-Content-Type-Options'=>'nosniff']);
    }
    public function index(Request $request)
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.theater', 'tickets.seat'])
            ->where('User_ID', $request->user()->id)->orderByDesc('Booking_Date')->paginate(10);
        return view('booking-history', compact('bookings'));
    }

    public function show(Request $request, Booking $booking)
    {
        abort_unless((int) $booking->User_ID === (int) $request->user()->id || $request->user()->is_admin, 403);
        $booking->load(['showtime.movie', 'showtime.theater', 'tickets.seat']);
        return view('booking', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_unless((int) $booking->User_ID === (int) $request->user()->id, 403);
        abort_if(strtotime($booking->showtime->Show_Date.' '.$booking->showtime->Show_Time) <= time(), 422);
        DB::transaction(function () use ($booking) {
            $booking->tickets()->delete();
            $booking->Status = 'ยกเลิก';
            $booking->save();
        });
        return redirect()->route('booking-history.index')->with('status', 'ยกเลิกการจองแล้ว');
    }
}


