@extends('layouts.app')

@section('title', __('หน้าแรก'))

@section('header')
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('หน้าแรก') }}
    </h1>
@endsection

@section('content')
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('สวัสดี') }}
                </div>
            </div>
        </div>
    </section>
@endsection
