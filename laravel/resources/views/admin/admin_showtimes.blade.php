@extends('layouts.app')
@section('title', 'จัดการรอบฉาย')
@section('header')<h1 class="text-2xl font-bold">
    จัดการรอบฉาย</h1>@endsection
@section('content')
    <div class="mx-auto max-w-6xl space-y-6 px-4 py-10">
        <details
            class="cinema-panel p-6"
            open
        >
            <summary class="mb-5 text-xl font-bold cursor-pointer">＋ เพิ่มรอบฉาย</summary>
            <form
                method="POST"
                action="{{ route('admin.showtimes.store') }}"
                class="grid gap-4 md:grid-cols-3"
            >@csrf
                <select
                    class="cinema-input"
                    aria-label="ภาพยนตร์"
                    name="Movie_ID"
                    required
                >
                    <option value="">เลือกภาพยนตร์</option>
                    @foreach ($movies as $movie)
                        <option value="{{ $movie->Movie_ID }}">{{ $movie->Title }}</option>
                    @endforeach
                </select>
                <select
                    class="cinema-input"
                    aria-label="โรงภาพยนตร์"
                    name="Theater_ID"
                    required
                >
                    <option value="">เลือกโรง</option>
                    @foreach ($theaters as $theater)
                        <option value="{{ $theater->Theater_ID }}">{{ $theater->Theater_Name }} ·
                            {{ $theater->Theater_Location }}</option>
                    @endforeach
                </select>
                <input
                    class="cinema-input"
                    name="Language"
                    placeholder="ภาษา / คำบรรยาย"
                ><input
                    class="cinema-input"
                    aria-label="วันที่ฉาย"
                    type="date"
                    name="Show_Date"
                    min="{{ today()->toDateString() }}"
                    required
                ><input
                    class="cinema-input"
                    aria-label="เวลาฉาย"
                    type="time"
                    name="Show_Time"
                    required
                ><button class="cinema-button">เพิ่มรอบฉาย</button>
            </form>
        </details>
        <form
            method="GET"
            class="cinema-panel admin-filter"
        ><label>ภาพยนตร์<select
                    class="cinema-input"
                    name="movie"
                >
                    <option value="">ทุกเรื่อง</option>
                    @foreach ($movies as $movie)
                        <option
                            value="{{ $movie->Movie_ID }}"
                            @selected(request('movie') == $movie->Movie_ID)
                        >{{ $movie->Title }}</option>
                    @endforeach
                </select>
            </label><label>โรงภาพยนตร์<select
                    class="cinema-input"
                    name="theater"
                >
                    <option value="">ทุกโรง</option>
                    @foreach ($theaters as $theater)
                        <option
                            value="{{ $theater->Theater_ID }}"
                            @selected(request('theater') == $theater->Theater_ID)
                        >
                            {{ $theater->Theater_Name }}</option>
                    @endforeach
                </select>
            </label><label>วันที่ฉาย<input
                    class="cinema-input"
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                ></label><button class="cinema-button">กรองรอบฉาย</button><a href="{{ route('admin.showtimes') }}">ล้าง</a>
        </form>
        <div class="space-y-4">
            @forelse($showtimes as $showtime)
                <details class="cinema-panel p-5">
                    <summary class="admin-show-summary">
                        <div><strong>{{ $showtime->movie->Title }}</strong><small>{{ $showtime->theater->Theater_Name }} ·
                                {{ $showtime->Language }}</small></div><span>{{ $showtime->Show_Date }} ·
                            {{ substr($showtime->Show_Time, 0, 5) }}<br>แก้ไขรอบฉาย ⌄</span>
                    </summary>
                    <form
                        method="POST"
                        action="{{ route('admin.showtimes.update', $showtime) }}"
                        class="grid gap-3 md:grid-cols-5"
                    >@csrf @method('PUT')
                        <select
                            class="cinema-input"
                            name="Movie_ID"
                        >
                            @foreach ($movies as $movie)
                                <option
                                    value="{{ $movie->Movie_ID }}"
                                    @selected($showtime->Movie_ID === $movie->Movie_ID)
                                >{{ $movie->Title }}
                                </option>
                            @endforeach
                        </select>
                        <select
                            class="cinema-input"
                            name="Theater_ID"
                        >
                            @foreach ($theaters as $theater)
                                <option
                                    value="{{ $theater->Theater_ID }}"
                                    @selected($showtime->Theater_ID === $theater->Theater_ID)
                                >
                                    {{ $theater->Theater_Name }}</option>
                            @endforeach
                        </select>
                        <input
                            class="cinema-input"
                            aria-label="วันที่ฉาย"
                            type="date"
                            name="Show_Date"
                            value="{{ $showtime->Show_Date }}"
                            required
                        ><input
                            class="cinema-input"
                            aria-label="เวลาฉาย"
                            type="time"
                            name="Show_Time"
                            value="{{ substr($showtime->Show_Time, 0, 5) }}"
                            required
                        ><input
                            class="cinema-input"
                            name="Language"
                            value="{{ $showtime->Language }}"
                            placeholder="ภาษา"
                        ><button class="cinema-button md:col-span-5">บันทึก</button>
                    </form>
                    <form
                        method="POST"
                        action="{{ route('admin.showtimes.destroy', $showtime) }}"
                        class="mt-2"
                        onsubmit="return confirm('ลบรอบฉายนี้?')"
                    >@csrf @method('DELETE')<button class="text-sm text-red-300 underline">ลบรอบฉาย</button></form>
                </details>
            @empty <p>ไม่พบรอบฉาย</p>
            @endforelse
        </div>{{ $showtimes->links() }}
    </div>
@endsection
