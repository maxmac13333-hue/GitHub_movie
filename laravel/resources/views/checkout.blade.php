@extends('layouts.customer')
@section('title', 'ตรวจสอบการจอง')
@section('content')
    <div class="wrap checkout-layout">
        <div class="breadcrumb"><a href="{{ route('showtimes.seats', $showtime) }}">← กลับไปเลือกที่นั่ง</a></div>
        <div class="stepper"><span><b>✓</b> เลือกที่นั่ง</span><i></i><span class="active"><b>2</b>
                ตรวจสอบการจอง</span><i></i><span><b>3</b> รับบัตรการจอง</span></div>
        <div class="booking-heading"><img
                src="{{ $showtime->movie->poster_url }}"
                alt="{{ $showtime->movie->Title }}"
            >
            <div>
                <h1>{{ $showtime->movie->Title }}</h1>
                <p>{{ \Carbon\Carbon::parse($showtime->Show_Date)->locale('th')->translatedFormat('j M Y') }} ·
                    {{ substr($showtime->Show_Time, 0, 5) }} น. · {{ $showtime->theater->Theater_Name }}
                    ({{ $showtime->theater->format }})</p>
            </div>
        </div>
        <section class="review-panel"><span class="eyebrow">ONE STEP CLOSER TO YOUR MOVIE</span>
            <h2>ตรวจสอบก่อนยืนยัน</h2>
            <div class="review-list">
                @foreach ($seats as $seat)
                    <div><span>ที่นั่ง <strong>{{ $seat->Seat_No }}</strong> <span class="muted small">·
                                {{ explode(' - ', $seat->Seat_Type)[0] }}</span></span><strong>{{ number_format($seat->price) }}
                            บาท</strong></div>
                @endforeach
            </div>
            <div class="summary-total"><span>รวม {{ $seats->count() }}
                    ที่นั่ง</span><strong>{{ number_format($seats->sum('price')) }} <small>บาท</small></strong></div>
            <div class="payment-note"><span>ⓘ</span>
                <p>ชำระเงินจำลองภายใน 10 นาที<br>หลังยืนยัน ให้แนบรูปสลิปตัวอย่าง ไม่ต้องโอนเงินจริง
                    หากหมดเวลาระบบจะคืนที่นั่ง</p>
            </div>
            <form
                method="POST"
                action="{{ route('bookings.store', $showtime) }}"
            >@csrf @foreach ($seats as $seat)
                    <input
                        type="hidden"
                        name="seats[]"
                        value="{{ $seat->Seat_ID }}"
                    >
                @endforeach
                <button class="btn">ยืนยันการจอง <span>→</span></button>
            </form>
            <p class="summary-footnote">กรุณาตรวจสอบวันที่ รอบฉาย และที่นั่งให้ถูกต้อง</p>
        </section>
    </div>
@endsection
