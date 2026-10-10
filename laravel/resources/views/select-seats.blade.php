@extends('layouts.customer')
@section('title','เลือกที่นั่ง · '.$movie->Title)
@section('content')
<div class="wrap" x-data="seatBooking(@js(route('showtimes.availability',$showtime)), @js($takenSeatIds))">
 <div class="breadcrumb"><a href="{{ route('movies.index') }}">ภาพยนตร์</a><span>›</span><a href="{{ route('movies.showtimes',[$movie,'date'=>$showtime->Show_Date]) }}">{{ $movie->Title }}</a><span>›</span><span>เลือกที่นั่ง</span></div>
 <div class="stepper"><span class="active"><b>1</b> เลือกที่นั่ง</span><i></i><span><b>2</b> ตรวจสอบการจอง</span><i></i><span><b>3</b> รับบัตรการจอง</span></div>
 <div class="booking-heading"><img src="{{ $movie->poster_url }}" alt="{{ $movie->Title }}"><div><h1>{{ $movie->Title }}</h1><p>{{ \Carbon\Carbon::parse($showtime->Show_Date)->locale('th')->translatedFormat('D j M Y') }} · {{ substr($showtime->Show_Time,0,5) }} น. &nbsp; | &nbsp; {{ $showtime->theater->Theater_Name }} ({{ $showtime->theater->format }}) · {{ $showtime->Language }}</p></div><a class="screen-format" href="{{ route('movies.showtimes',[$movie,'date'=>$showtime->Show_Date]) }}">เปลี่ยนรอบฉาย ↗</a></div>
 <div class="notice error" x-show="message" x-cloak role="status" x-text="message"></div>
 <div class="seat-layout">
  <section class="seat-panel"><div class="panel-heading"><h2>เลือกมุมชมที่เป็นคุณ</h2><span class="live-label"><i></i> อัปเดตที่นั่งทุก 15 วินาที</span></div><div class="screen-curve">SCREEN / จอภาพยนตร์</div>
   @php($seatMap = $seats->keyBy(fn($seat) => $seat->pos_x.'-'.$seat->pos_y))
   <div class="seat-map" tabindex="0" aria-label="ผังที่นั่ง เลื่อนแนวนอนได้"><div class="seat-map-inner">
   @for($row=1;$row<=$rowCount;$row++)
    <div class="seat-row"><span class="row-label">{{ chr(64+$row) }}</span>
     @for($col=1;$col<=$columnCount;$col++)@php($seat = $seatMap->get($col.'-'.$row))
      @if($seat)
       <button type="button" class="theater-seat {{ $seat->price===350 ? 'vip' : ($seat->price===240 ? 'premium' : '') }} {{ in_array($col,[6,12]) && $col<$columnCount ? 'aisle-after' : '' }}"
        data-id="{{ $seat->Seat_ID }}" data-no="{{ $seat->Seat_No }}" data-price="{{ $seat->price }}"
        @click="toggle($el.dataset)" :disabled="taken.includes({{ $seat->Seat_ID }}) || {{ strtotime($showtime->Show_Date.' '.$showtime->Show_Time)<=time() ? 'true' : 'false' }}"
        :class="{selected: selected.some(s=>s.id==={{ $seat->Seat_ID }}),taken: taken.includes({{ $seat->Seat_ID }})}"
        :aria-pressed="selected.some(s=>s.id==={{ $seat->Seat_ID }})"
        aria-label="ที่นั่ง {{ $seat->Seat_No }} ราคา {{ $seat->price }} บาท" title="{{ $seat->Seat_No }} · {{ $seat->Seat_Type }}">
        <svg viewBox="0 0 28 31" aria-hidden="true"><path d="M5 19V7a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v12M3 15h3v7h16v-7h3v11H3zM6 26v3M22 26v3" stroke-linejoin="round"/></svg><span class="seat-number">{{ $col }}</span>
       </button>
      @else<span class="seat-space {{ in_array($col,[6,12]) && $col<$columnCount ? 'aisle-after' : '' }}" aria-hidden="true"></span>@endif
     @endfor
     <span class="row-label">{{ chr(64+$row) }}</span>
    </div>
   @endfor
   </div></div>
   @if($seats->isEmpty())<p class="seat-hint">โรงนี้ยังไม่มีที่นั่ง กรุณาเลือกรอบฉายอื่น</p>@endif
   <div class="seat-legend"><span><i></i>Deluxe · 180 ฿</span><span><i class="legend-premium"></i>Premium · 240 ฿</span><span><i class="legend-vip"></i>VIP · 350 ฿</span><span><i class="legend-selected"></i>เลือกแล้ว</span><span><i class="legend-taken"></i>จองแล้ว</span></div>
   <p class="seat-hint">แถว A อยู่ใกล้จอภาพ · เลื่อนแผนผังซ้าย–ขวาได้บนมือถือ</p>
  </section>
  <aside class="summary-panel"><h2>การจองของคุณ</h2><p class="subtitle">เลือกที่นั่งได้สูงสุด 8 ที่นั่งต่อรายการ</p>
   <div class="summary-row"><span>โรงภาพยนตร์</span><strong>{{ $showtime->theater->Theater_Name }} ({{ $showtime->theater->format }})</strong></div>
   <div class="summary-row"><span>รอบฉาย</span><strong>{{ substr($showtime->Show_Time,0,5) }} น.</strong></div>
   <div class="summary-row"><span>ที่นั่งว่าง</span><strong><span x-text="{{ $seats->count() }} - taken.length"></span> / {{ $seats->count() }}</strong></div>
   <div class="summary-divider"></div><span class="muted small">ที่นั่งที่เลือก <span x-text="selected.length"></span> ที่</span>
   <div class="selected-seats" x-show="selected.length"><template x-for="seat in selected" :key="seat.id"><button type="button" class="selected-chip" @click="remove(seat.id)" :aria-label="'ยกเลิกที่นั่ง '+seat.no"><span x-text="seat.no"></span> ×</button></template></div>
   <p class="selection-empty" x-show="!selected.length">แตะที่นั่งบนแผนผังเพื่อเริ่มเลือก</p>
   <div class="summary-divider"></div><div class="summary-total"><span>รวมทั้งหมด</span><strong><span x-text="total.toLocaleString()">0</span> <small>บาท</small></strong></div>
   <form method="POST" action="{{ route('bookings.checkout',$showtime) }}">@csrf<template x-for="seat in selected" :key="seat.id"><input type="hidden" name="seats[]" :value="seat.id"></template><button class="btn" :disabled="!selected.length">ตรวจสอบการจอง <span>→</span></button></form><p class="summary-footnote">ที่นั่งจะถูกจองหลังยืนยันรายการสำเร็จ</p>
  </aside>
 </div>
</div>
@endsection

