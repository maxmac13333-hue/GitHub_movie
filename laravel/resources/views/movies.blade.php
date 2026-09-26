@extends('layouts.app')

@section('title', __('ภาพยนตร์'))

@section('header')
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('ภาพยนตร์') }}
    </h1>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($movies as $movie)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                        <!-- โปสเตอร์หนัง -->
                        <div class="h-64 bg-gray-200 overflow-hidden">
                            @if($movie->Poster)
                                <img src="{{ asset('movies_png/' . $movie->Poster) }}" alt="{{ $movie->Title }}" class="w-40 h-80 object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500">ไม่มีรูปภาพโปสเตอร์</div>
                            @endif
                        </div>

                        <!-- รายละเอียดหนัง -->
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $movie->Title }}</h2>
                                <p class="text-sm text-gray-600 mt-1">แนว: {{ $movie->Genre }} | ความยาว: {{ $movie->Duration }} นาที</p>
                                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $movie->Synopsis }}</p>
                            </div>

                            <a href="{{ route('movies.showtimes', ['movie' => $movie->Movie_ID]) }}" class="mt-4 inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                ดูรอบฉาย
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
    </div>
</div>
@endsection