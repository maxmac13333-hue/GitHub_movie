@extends('layouts.app')
@section('title', 'หน้าแรก')
@section('header')<div>
        <p class="text-sm font-semibold uppercase tracking-[.3em] text-amber-300">WELCOME TO MOVIE NIGHT</p>
        <h1 class="mt-2 text-3xl font-black md:text-5xl">เลือกเรื่องที่ใช่ แล้วจองที่นั่งเลย</h1>
        <p class="mt-3 text-slate-400">ภาพยนตร์ รอบฉาย และที่นั่ง ทุกอย่างอยู่ในที่เดียว</p>
</div>@endsection
@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-7 flex items-end justify-between">
            <div>
                <p class="text-sm text-amber-300">NOW SHOWING</p>
                <h2 class="text-2xl font-bold">ภาพยนตร์ทั้งหมด</h2>
            </div><a
                href="{{ route('movies.index') }}"
                class="text-sm text-amber-300"
            >ดูทั้งหมด →</a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($movies as $movie)
                <a
                    href="{{ route('movies.showtimes', $movie) }}"
                    class="cinema-panel group overflow-hidden"
                >
                    <div class="h-72 bg-slate-800"><img
                            src="{{ asset('movies_png/' . ($movie->Poster ?: 'Avatar_The_Way_of_Water.png')) }}"
                            alt="{{ $movie->Title }}"
                            class="h-full w-full object-cover transition group-hover:scale-105"
                        >
                    </div>
                    <div class="p-5">
                        <p class="text-xs uppercase tracking-wider text-amber-300">{{ $movie->Genre ?: 'ภาพยนตร์' }}</p>
                        <h3 class="mt-2 text-xl font-bold">{{ $movie->Title }}</h3>
                        <p class="mt-2 text-sm text-slate-400">{{ $movie->Duration }} นาที ·
                            {{ $movie->showtimes->count() }} รอบฉาย</p><span
                            class="mt-5 inline-block text-sm font-bold text-amber-300"
                        >ดูรอบฉาย →</span>
                    </div>
                </a>
            @empty <div class="cinema-panel col-span-full p-10 text-center">ยังไม่มีภาพยนตร์</div>
            @endforelse
        </div>
    </div>
@endsection
