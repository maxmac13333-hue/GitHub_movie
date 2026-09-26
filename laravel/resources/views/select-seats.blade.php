@extends('layouts.app')

@section('title', __('เลือกที่นั่ง'))

@section('header')
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">เลือกที่นั่ง</h1>
@endsection

@section('content')
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 md:p-8">
                <h2 class="text-2xl font-bold text-gray-900">{{ $movie->Title }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    รอบ {{ $showtime->Show_Time }} · {{ $showtime->theater->Theater_Name ?? 'โรงภาพยนตร์' }}
                </p>

                <div class="mt-8 rounded-md bg-gray-100 py-2 text-center text-sm font-medium text-gray-600">
                    หน้าจอภาพยนตร์
                </div>

                <div class="mt-8 grid grid-cols-4 gap-3 sm:grid-cols-6 md:grid-cols-8">
                    @forelse ($seats as $seat)
                        <button type="button" class="rounded-md border border-indigo-200 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-50">
                            {{ $seat->Seat_Number ?? $seat->Seat_Name ?? $seat->Seat_ID }}
                        </button>
                    @empty
                        <p class="col-span-full text-center text-sm text-gray-500">ยังไม่มีข้อมูลที่นั่ง</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection