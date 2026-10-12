@extends('layouts.app')
@section('title', 'จัดการภาพยนตร์')
@section('header')<h1>
    จัดการภาพยนตร์</h1>@endsection
@section('content')
    <div class="admin-stats">
        <div><span>ภาพยนตร์ทั้งหมด</span><strong>{{ \App\Models\Movie::count() }} <small>เรื่อง</small></strong></div>
        <div><span>รอบฉายตั้งแต่วันนี้</span><strong>{{ \App\Models\Showtime::where('Show_Date', '>=', today()->toDateString())->count() }}
                <small>รอบ</small></strong></div>
        <div><span>โรงภาพยนตร์</span><strong>{{ \App\Models\Theater::count() }} <small>โรง</small></strong></div>
    </div>
    <details
        class="cinema-panel admin-create"
        @if ($errors->any() && !old('_method')) open @endif
    >
        <summary>＋ เพิ่มภาพยนตร์ใหม่ <span>กรอกข้อมูลหนังที่ต้องการเปิดให้จอง</span></summary>
        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('admin.movies.store') }}"
            class="admin-fields"
        >@csrf
            @include('admin.movie-fields', ['record' => null])<button class="cinema-button">เพิ่มภาพยนตร์</button></form>
    </details>
    <div class="admin-toolbar">
        <div>
            <h2>คลังภาพยนตร์</h2>
            <p>{{ $movies->count() }} เรื่องที่แสดง</p>
        </div>
        <form method="GET"><input
                class="cinema-input"
                name="q"
                value="{{ request('q') }}"
                placeholder="ค้นหาชื่อไทยหรืออังกฤษ..."
                aria-label="ค้นหาภาพยนตร์"
            ><button class="cinema-button">ค้นหา</button>
            @if (request('q'))
                <a href="{{ route('admin.movies') }}">ล้าง</a>
            @endif
        </form>
    </div>
    <div class="admin-movie-grid">
        @forelse($movies as $movie)
            <article class="cinema-panel admin-movie">
                <div class="admin-movie-summary"><img
                        src="{{ $movie->poster_url }}"
                        alt="{{ $movie->Title }}"
                    >
                    <div><span class="admin-badge">{{ $movie->Genre ?: 'ยังไม่ระบุประเภท' }}</span>
                        <h2>{{ $movie->Title }}</h2>
                        <p>{{ $movie->Thai_Title }}</p>
                        <p>{{ $movie->Duration }} นาที · {{ $movie->showtimes_count }} รอบฉาย</p><a
                            class="text-link"
                            href="{{ route('admin.showtimes', ['movie' => $movie->Movie_ID]) }}"
                        >จัดการรอบฉาย ↗</a>
                    </div>
                </div>
                <details @if (old('_record') == $movie->Movie_ID) open @endif>
                    <summary>แก้ไขข้อมูลภาพยนตร์ <span>⌄</span></summary>
                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route('admin.movies.update', $movie) }}"
                        class="admin-fields"
                    >@csrf @method('PUT')<input
                            type="hidden"
                            name="_record"
                            value="{{ $movie->Movie_ID }}"
                        >@include('admin.movie-fields', ['record' => $movie])<button class="cinema-button">บันทึกการแก้ไข</button></form>
                    <form
                        class="admin-delete"
                        method="POST"
                        action="{{ route('admin.movies.destroy', $movie) }}"
                        onsubmit="return confirm('ยืนยันลบภาพยนตร์นี้?')"
                    >@csrf
                        @method('DELETE')<button>ลบภาพยนตร์</button><span>ลบได้เมื่อไม่มีรอบฉาย</span></form>
                </details>
        </article>@empty<div class="cinema-panel p-8">ไม่พบภาพยนตร์ ลองค้นหาด้วยคำอื่น</div>
        @endforelse
    </div>
@endsection
