@extends('layouts.customer')
@section('title', 'บัตรการจอง #' . $booking->Booking_ID)
@section('content')
    <div class="wrap">
        <div class="breadcrumb"><a href="{{ route('booking-history.index') }}">← การจองของฉัน</a></div>
        <div class="section-title">
            <div><span class="eyebrow">SEE YOU AT THE MOVIES</span>
                <h2>บัตรการจองของคุณ</h2>
            </div>
        </div>
        @include('payment')
        <article class="digital-ticket">
            <div class="ticket-top"><img
                    src="{{ $booking->showtime->movie->poster_url }}"
                    alt="{{ $booking->showtime->movie->Title }}"
                >
                <div><span class="eyebrow">FF CINEMA · BOOKING PASS</span>
                    <h2>{{ $booking->showtime->movie->Title }}</h2><span
                        class="status-pill {{ $booking->Status === 'ยกเลิก' ? 'cancelled' : '' }}"
                    >{{ $booking->payment_label }}</span>
                </div>
            </div>
            <div class="ticket-body">
                <div>
                    <small>วันที่เข้าชม</small><strong>{{ \Carbon\Carbon::parse($booking->showtime->Show_Date)->locale('th')->translatedFormat('j F Y') }}</strong>
                </div>
                <div><small>เวลา</small><strong>{{ substr($booking->showtime->Show_Time, 0, 5) }} น.</strong></div>
                <div><small>โรงภาพยนตร์</small><strong>{{ $booking->showtime->theater->Theater_Name }}
                        ({{ $booking->showtime->theater->format }})</strong></div>
                <div><small>ที่นั่ง</small><strong>{{ $booking->Seat_Summary ?: '—' }}</strong></div>
                <div><small>เลขการจอง</small><strong>#{{ $booking->Booking_ID }}</strong></div>
                <div><small>ยอดรวม</small><strong>{{ number_format($booking->Total_Price) }} บาท</strong></div>
            </div>
            @if (in_array($booking->payment_state, ['paid', 'legacy']) && $booking->Status !== 'ยกเลิก')
                <div class="ticket-codes">
                    @foreach ($booking->tickets as $ticket)
                        <p>{{ $ticket->seat->Seat_No }} &nbsp; {{ $ticket->Ticket_Code }}</p>
                    @endforeach
                    <span class="muted small">
                        แสดงบัตรนี้ที่เคาน์เตอร์โรงภาพยนตร์ก่อนเข้าชม</span>
                </div>
            @endif
        </article>
        @if (
            $booking->Status !== 'ยกเลิก' &&
                strtotime($booking->showtime->Show_Date . ' ' . $booking->showtime->Show_Time) > time() &&
                (int) $booking->User_ID === (int) auth()->id())
            <form
                action="{{ route('bookings.cancel', $booking) }}"
                method="POST"
                class="text-center"
                onsubmit="return confirm('ต้องการยกเลิกการจองนี้หรือไม่?')"
            >@csrf<button class="text-sm text-slate-400 underline">ยกเลิกการจองนี้</button></form>
        @endif
    </div>
@endsection
