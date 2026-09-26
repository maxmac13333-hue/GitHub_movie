@extends('layouts.app')

@section('title', __('จัดการการจอง'))
@section('header')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('จัดการการจอง') }}
    </h1>

        @if (auth()->user()->is_admin)
            <details class="relative">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('ผู้ดูแล') }}
                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </summary>
                <nav aria-label="เมนูผู้ดูแล" class="absolute right-0 z-50 mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                    <a href="{{ route('admin.movies') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('จัดการภาพยนตร์') }}</a>
                    <a href="{{ route('admin.bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('จัดการการจอง') }}</a>
                    <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('จัดการผู้ใช้') }}</a>
                    <a href="{{ route('admin.theaters') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('จัดการโรงภาพยนตร์') }}</a>
                </nav>
            </details>
        @endif
</div>
@endsection

@section('content')
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg text-gray-900">
                {{ __('หน้านี้เตรียมไว้สำหรับพัฒนาต่อ') }}
            </div>
        </div>
    </section>
@endsection