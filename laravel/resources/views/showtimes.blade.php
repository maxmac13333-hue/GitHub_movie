@extends('layouts.customer')
@section('title', $movie->Title)
@section('content')
    <div class="wrap">
        <div class="breadcrumb"><a
                href="{{ route('movies.index') }}">ภาพยนตร์</a><span>›</span><span>{{ $movie->Title }}</span></div>
        <section class="detail-hero">
            <img
                class="detail-poster"
                src="{{ $movie->poster_url }}"
                alt="โปสเตอร์ {{ $movie->Title }}"
            >
            <div class="detail-content"><span class="eyebrow">ON THE BIG SCREEN</span>
                <h1>{{ $movie->Title }}</h1>
                <h2>{{ $movie->Thai_Title }}</h2>
                <div class="detail-chips"><span>{{ $movie->Genre }}</span><span>◷ {{ $movie->Duration }} นาที</span></div>
                <p class="detail-synopsis">{{ $movie->Synopsis }}</p>
                <p class="director">ผู้กำกับ <strong>{{ $movie->Director }}</strong></p>
            </div>
        </section>
        <section class="showtime-section">
            <div class="section-title">
                <div><span class="eyebrow">CHOOSE YOUR SHOWTIME</span>
                    <h2>เลือกรอบที่ใช่สำหรับคุณ</h2>
                </div><span class="result-count">เวลาในประเทศไทย</span>
            </div>
            <nav
                class="date-strip"
                aria-label="เลือกวันที่เข้าชม"
            >
                @foreach ($dates as $date)
                    @php($day = \Carbon\Carbon::parse($date)->locale('th'))
                    <a
                        class="date-pill {{ $selectedDate === $date ? 'active' : '' }}"
                        href="{{ route('movies.showtimes', [$movie, 'date' => $date]) }}"
                        @if ($selectedDate === $date) aria-current="date" @endif
                    ><span>{{ $day->isToday() ? 'วันนี้' : $day->translatedFormat('D') }}</span><strong>{{ $day->format('d') }}</strong><span>{{ $day->translatedFormat('M Y') }}</span></a>
                @endforeach
            </nav>
            @forelse($groups as $shows)@php($theater = $shows->first()->theater)
                <article class="showtime-hall">
                    <div class="hall-heading"><span class="hall-icon">▤</span>
                        <div>
                            <h3>{{ $theater->Theater_Name }}</h3>
                            <p>{{ $theater->Theater_Location }}</p>
                            <p>{{ $theater->seats->count() }} ที่นั่ง · {{ $theater->format }}</p>
                        </div>
                    </div>
                    <div class="times-grid">
                        @foreach ($shows as $show)
                            <a
                                class="time-button"
                                href="{{ route('showtimes.seats', $show) }}"
                            ><strong>{{ substr($show->Show_Time, 0, 5) }}</strong><span>{{ $show->Language }}</span><em>ว่าง
                                    {{ max(0, $theater->seats->count() - $show->tickets_count) }} ที่นั่ง</em></a>
                        @endforeach
                    </div>
                </article>
            @empty <div class="empty-state">
                    <h3>ยังไม่มีรอบฉายสำหรับวันนี้</h3>
                    <p>เลือกวันอื่น หรือกลับไปค้นหาภาพยนตร์เรื่องใหม่</p><a
                        class="btn btn-outline"
                        href="{{ route('movies.index') }}"
                    >เลือกภาพยนตร์</a>
                </div>
            @endforelse
        </section>
    </div>
@endsection
