@extends('layouts.app')

@section('title', __('จัดการโรงภาพยนตร์'))
@section('header')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('จัดการโรงภาพยนตร์') }}
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
    <section class="py-12" x-data="{ 
        showAddTheater: {{ $errors->has('Theater_Name') || $errors->has('Theater_Location') ? 'true' : 'false' }},
        editMode: false,
        theaterId: null,
        theaterName: @js(old('Theater_Name', '')),
        theaterLocation: @js(old('Theater_Location', '')),
        
        openCreate() {
            this.editMode = false;
            this.theaterId = null;
            this.theaterName = '';
            this.theaterLocation = '';
            this.showAddTheater = true;
        },
        
        openEdit(id, name, location) {
            this.editMode = true;
            this.theaterId = id;
            this.theaterName = name;
            this.theaterLocation = location;
            this.showAddTheater = true;
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('รายชื่อโรงภาพยนตร์') }}</h2>
                    <button type="button" @click="openCreate()" class="inline-flex shrink-0 items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('เพิ่มโรงภาพยนตร์') }}
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">{{ __('รหัส') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">{{ __('ชื่อโรง') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">{{ __('สถานที่') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">{{ __('จำนวนที่นั่ง') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">{{ __('แก้ไข') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">{{ __('จัดการ') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($theaters as $theater)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $theater->Theater_ID }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $theater->Theater_Name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $theater->Theater_Location }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $theater->seats_count }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm space-x-3">
                                        <button type="button" @click="openEdit({{ $theater->Theater_ID }}, @js($theater->Theater_Name), @js($theater->Theater_Location))" class="font-medium text-indigo-700 hover:text-indigo-900">{{ __('แก้ไข') }}</button>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4  text-sm space-x-3">
                                        <a href="{{ route('admin.theaters.edit', $theater->Theater_ID) }}" class="font-medium text-indigo-700 hover:text-indigo-900">{{ __('จัดการที่นั่ง') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">{{ __('ไม่พบข้อมูลโรงภาพยนตร์') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ฟอร์มสำหรับ เพิ่ม / แก้ไข โรงภาพยนตร์ -->
                <form x-cloak x-show="showAddTheater" 
                      :action="editMode ? '{{ url('admin/theaters') }}/' + theaterId : '{{ route('admin.theaters.store') }}'" 
                      method="POST" 
                      class="space-y-4 border-t border-gray-200 bg-gray-50 px-6 py-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900" x-text="editMode ? '{{ __('แก้ไขข้อมูลโรงภาพยนตร์') }}' : '{{ __('เพิ่มโรงภาพยนตร์') }}'"></h3>
                        <button type="button" @click="showAddTheater = false" class="text-sm text-gray-500 hover:text-gray-700">{{ __('ปิด') }}</button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="Theater_Name" class="mb-1 block text-sm font-medium text-gray-700">{{ __('ชื่อโรงภาพยนตร์') }}</label>
                            <input id="Theater_Name" name="Theater_Name" type="text" maxlength="150" required x-model="theaterName" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="Theater_Location" class="mb-1 block text-sm font-medium text-gray-700">{{ __('สถานที่') }}</label>
                            <input id="Theater_Location" name="Theater_Location" type="text" maxlength="200" required x-model="theaterLocation" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700" x-text="editMode ? '{{ __('บันทึกการแก้ไข') }}' : '{{ __('บันทึกโรงภาพยนตร์') }}'"></button>
                        <button type="button" @click="showAddTheater = false" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ __('ยกเลิก') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
