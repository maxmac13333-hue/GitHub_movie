@extends('layouts.customer')
@section('title','ภาพยนตร์และรอบฉาย')
@section('content')
<div class="wrap catalog-page">
 @if($featured && empty($filters['q']) && empty($filters['genre']) && empty($filters['date']))
 <section class="feature-banner">
  <div class="feature-copy"><span class="eyebrow"><i></i> MAKE TIME FOR A MOVIE</span><h1>ให้หนังเรื่องโปรด<br>เป็นช่วงเวลาดี ๆ <span>ของคุณ</span></h1><p>เลือกเรื่องที่อยากดู รอบเวลาที่ใช่<br>แล้วเจอกันบนจอใหญ่ที่ FF Cinema</p><a href="{{ route('movies.showtimes',$featured) }}" class="btn">สำรวจรอบฉาย <span>↗</span></a><span class="hero-note">เลือกที่นั่งได้เอง · จองง่ายในไม่กี่ขั้นตอน</span></div>
  <div class="feature-art" aria-label="ภาพยนตร์แนะนำ">
   <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
   @foreach($spotlights as $spotlight)<img class="hero-poster side-poster poster-{{ $loop->index }}" src="{{ $spotlight->poster_url }}" alt="{{ $spotlight->Title }}">@endforeach
   <a href="{{ route('movies.showtimes',$featured) }}" class="hero-feature"><img src="{{ $featured->poster_url }}" alt="{{ $featured->Title }}"><div><span>เรื่องแนะนำ</span><strong>{{ $featured->Title }}</strong></div></a>
   <span class="art-star">✦</span><div class="floating-ticket"><span class="ticket-icon">▰</span><div><strong>YOUR NEXT MOVIE</strong><span>ที่นั่งดี ๆ รอคุณอยู่</span></div><span>✦</span></div>
  </div>
 </section>
 @endif
 <section class="catalog-section" id="films">
  <div class="section-title"><div><span class="eyebrow">FIND YOUR NEXT FAVORITE</span><h2>วันนี้ ดูเรื่องไหนดี?</h2></div><span class="result-count">{{ $movies->count() }} เรื่องให้เลือกชม</span></div>
  <form method="GET" action="{{ route('movies.index') }}" class="search-bar">
   <div class="search-field"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="10.8" cy="10.8" r="7.2"/><path d="m16 16 5 5"/></svg><input aria-label="ค้นหาภาพยนตร์" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="ค้นหาชื่อภาพยนตร์ ชื่อไทย หรือผู้กำกับ..." maxlength="100"></div>
   <select name="genre" aria-label="ประเภทภาพยนตร์"><option value="">ทุกประเภท</option>@foreach($genres as $genre)<option value="{{ $genre }}" @selected(($filters['genre']??'')===$genre)>{{ $genre }}</option>@endforeach</select>
   <input type="date" name="date" aria-label="วันที่เข้าชม" value="{{ $filters['date'] ?? '' }}" min="{{ today()->toDateString() }}">
   <button class="btn">ค้นหา</button>
  </form>
  <div class="catalog-toolbar"><div class="catalog-tab">ภาพยนตร์ที่เปิดจอง <span>{{ $movies->count() }}</span></div>@if(array_filter($filters))<a class="text-link" href="{{ route('movies.index') }}">ล้างตัวกรอง ×</a>@else<span class="muted small">รอบฉายและที่นั่งอัปเดตตามการจอง</span>@endif</div>
  <div class="movie-grid">
   @forelse($movies as $movie)
   <article class="movie-card">
    <a class="poster-link" href="{{ route('movies.showtimes',$movie) }}"><img src="{{ $movie->poster_url }}" alt="โปสเตอร์ {{ $movie->Title }}" loading="lazy"><span class="poster-cta">ดูรายละเอียด <span>↗</span></span></a>
    <div class="movie-info"><span class="movie-genre">{{ $movie->Genre }}</span><h3><a href="{{ route('movies.showtimes',$movie) }}">{{ $movie->Title }}</a></h3><p class="thai-title">{{ $movie->Thai_Title }}</p><div class="movie-meta"><span>◷ {{ $movie->Duration }} นาที</span></div>
    @if($next = $movie->showtimes->first())<p class="next-show">รอบถัดไป {{ \Carbon\Carbon::parse($next->Show_Date)->locale('th')->translatedFormat('j M') }} <strong>{{ substr($next->Show_Time,0,5) }}</strong></p>@else<p class="next-show">รอตารางฉายใหม่</p>@endif
    <a class="btn btn-outline card-button" href="{{ route('movies.showtimes',$movie) }}">เลือกรอบฉาย <span>→</span></a></div>
   </article>
   @empty
   <div class="empty-state"><span class="empty-icon">⌕</span><h3>ยังไม่พบเรื่องที่คุณค้นหา</h3><p>ลองเปลี่ยนคำค้น ประเภท หรือวันที่เข้าชม</p><a class="btn btn-outline" href="{{ route('movies.index') }}">ดูภาพยนตร์ทั้งหมด</a></div>
   @endforelse
  </div>
 </section>
 <section class="how-strip"><div><span>01</span><div><strong>เลือกเรื่องที่ชอบ</strong><p>ค้นพบเรื่องโปรดเรื่องถัดไป</p></div></div><div><span>02</span><div><strong>เลือกรอบและที่นั่ง</strong><p>เลือกมุมชมที่เป็นคุณ</p></div></div><div><span>03</span><div><strong>รับบัตรการจอง</strong><p>พร้อมสำหรับช่วงเวลาดี ๆ</p></div></div></section>
</div>
@endsection


