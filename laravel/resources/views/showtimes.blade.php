@extends('layouts.app')

@section('title', __('เลือกรอบฉาย'))

@section('header')
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">เลือกรอบฉาย</h1>
@endsection

@section('content')
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                <div class="grid md:grid-cols-[220px_1fr]">
                    <img src="{{ asset($movie['image']) }}" alt="{{ $movie['title'] }}" class="w-40 h-80 object-cover md:h-full">
                    <div class="p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $movie['title'] }}</h2>
                        <p class="mt-2 text-sm text-gray-600">{{ $movie['genre'] }} · {{ $movie['duration'] }}</p>

                        <div class="mt-8">
                            <h3 class="mb-3 font-semibold text-gray-900">รอบฉายที่มีบริการ</h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse ($showtimes as $showtime)
                                    <a href="{{ route('showtimes.seats', ['showtime' => $showtime->Show_ID]) }}" class="rounded-md border border-indigo-200 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-50">
                                        {{ $showtime->Show_Time }}
                                        ({{ $showtime->theater->Theater_Name ?? 'โรงภาพยนตร์' }})
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500">ยังไม่มีรอบฉายในขณะนี้</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection