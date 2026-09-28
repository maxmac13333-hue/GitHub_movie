@extends('layouts.app')

@section('title', __('จัดการที่นั่ง'))
@section('header')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('จัดการที่นั่ง') }}</h1>
</div>
@endsection

@section('content')
    <section class="py-12">
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

    @php
        $seatMap = $selectedTheater->seats->keyBy(fn ($seat) => $seat->pos_x . '-' . $seat->pos_y);
    @endphp
    <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg p-6" 
         x-data="{ 
             selectedSeat: null, 
             selectedType: 'Deluxe - 180 บาท', 
             selectedPrice: 180,
             selectSeat(seat) {
                 this.selectedSeat = seat;
                 this.selectedType = seat.type || 'Deluxe - 180 บาท';
                 this.updatePrice();
             },
             updatePrice() {
                 if (this.selectedType.startsWith('Deluxe')) this.selectedPrice = 180;
                 else if (this.selectedType.startsWith('Premium')) this.selectedPrice = 240;
                 else if (this.selectedType.startsWith('VIP')) this.selectedPrice = 350;
             }
         }">
        
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-200 pb-5 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ __('แผนผังที่นั่ง: ') }} {{ $selectedTheater->Theater_Name }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $selectedTheater->Theater_Location }}</p>
            </div>
            <a href="{{ route('admin.theaters') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">{{ __('กลับไปรายชื่อโรงภาพยนตร์') }}</a>
        </div>

        <!-- Main Content: Grid Seat Map + Side Panel -->
        <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,1fr)_18rem]">
            <div class="min-w-0 rounded-lg border border-gray-200 bg-gray-50 p-4 sm:p-6">
                <div class="mx-auto w-full max-w-3xl overflow-x-auto">
                    <div class="mb-7 rounded bg-gray-200 py-2 text-center text-xs font-semibold tracking-widest text-gray-600">
                        SCREEN / {{ __('จอภาพ') }}
                    </div>
                    <div class="grid min-w-[420px] grid-cols-[28px_repeat(8,minmax(40px,1fr))] gap-x-2 gap-y-3">
                        <span></span>
                        @for ($x = 1; $x <= 8; $x++)
                            <span class="pb-1 text-center text-xs font-semibold text-gray-500">{{ $x }}</span>
                        @endfor

                        @for ($y = 1; $y <= 9; $y++)
                            <span class="flex items-center justify-center text-sm font-bold text-gray-700">{{ chr(64 + $y) }}</span>
                            @for ($x = 1; $x <= 8; $x++)
                                @php($seat = $seatMap->get($x . '-' . $y))
                                @if ($seat)
                                    @php($isBooked = in_array((int) $seat->Seat_ID, $bookedSeatIds, true))
                                    <button type="button"
                                        @click="selectSeat({ id: {{ $seat->Seat_ID }}, no: $el.dataset.seatNo, type: $el.dataset.seatType, booked: $el.dataset.booked === 'true' })"
                                        :style="
                                            selectedSeat?.id === {{ $seat->Seat_ID }} ? 'background-color: #fbbf24; color: #111827; outline: 2px solid #d97706;' : 
                                            ({{ $isBooked ? 'true' : 'false' }}) ? 'background-color: #6b7280; color: #ffffff;' :
                                            ('{{ $seat->Seat_Type }}'.includes('350') || '{{ $seat->Seat_Type }}'.includes('VIP')) ? 'background-color: #9333ea; color: #ffffff;' :
                                            ('{{ $seat->Seat_Type }}'.includes('240') || '{{ $seat->Seat_Type }}'.includes('Premium')) ? 'background-color: #2563eb; color: #ffffff;' :
                                            'background-color: #047857; color: #ffffff;'
                                        "
                                        class="flex aspect-square min-h-10 items-center justify-center rounded-md text-[11px] font-semibold shadow-sm transition-colors sm:text-xs"
                                        data-seat-no="{{ $seat->Seat_No }}"
                                        data-seat-type="{{ $seat->Seat_Type }}"
                                        data-booked="{{ $isBooked ? 'true' : 'false' }}"
                                        title="{{ $seat->Seat_No }}{{ $isBooked ? ' - ' . __('มีรายการจอง') : '' }}">
                                        {{ $seat->Seat_No }}
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('admin.seats.store', $selectedTheater->Theater_ID) }}">
                                        @csrf
                                        <input type="hidden" name="pos_x" value="{{ $x }}">
                                        <input type="hidden" name="pos_y" value="{{ $y }}">
                                        <button type="submit" class="flex aspect-square min-h-10 w-full items-center justify-center rounded-md border border-dashed border-gray-400 bg-white text-lg text-gray-500 transition-colors hover:border-emerald-700 hover:bg-emerald-50 hover:text-emerald-800" title="{{ __('เพิ่มที่นั่ง') }} {{ chr(64 + $y) }}{{ $x }}">+</button>
                                    </form>
                                @endif
                            @endfor
                        @endfor
                    </div>
                </div>
            </div>

            <!-- คอลัมน์ขวา: รวม Aside และ กล่องคำอธิบายสีด้านล่าง -->
            <div class="space-y-4">
                <aside class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <div>
                        <h3 class="mb-4 border-b border-gray-200 pb-3 text-base font-bold text-gray-800">{{ $selectedTheater->Theater_Name }}</h3>
                        
                        <!-- แสดงผลเมื่อคลิกเลือกที่นั่ง -->
                        <template x-if="selectedSeat">
                            <div class="space-y-4">
                                <div class="text-center py-4 bg-gray-50 rounded-md">
                                    <p class="text-sm text-gray-500">{{ __('ที่นั่งที่เลือก') }}</p>
                                    <p class="text-xl font-bold text-pink-600 mt-1" x-text="selectedSeat.no"></p>
                                </div>

                                <div x-show="!selectedSeat.booked" class="space-y-4">
                                    <form :action="'{{ url('/admin/seats') }}/' + selectedSeat.id" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('เปลี่ยนประเภทและราคา') }}</label>
                                            <select name="Seat_Type" x-model="selectedType" @change="updatePrice()" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="Deluxe - 180 บาท">Deluxe - 180 บาท</option>
                                                <option value="Premium - 240 บาท">Premium - 240 บาท</option>
                                                <option value="VIP - 350 บาท">VIP - 350 บาท</option>
                                            </select>
                                        </div>

                                        <div class="text-center">
                                            <p class="text-sm text-gray-500">{{ __('ราคาประเมิน') }}</p>
                                            <p class="text-lg font-semibold text-gray-900"><span x-text="selectedPrice"></span> บาท</p>
                                        </div>

                                        <button type="submit" class="w-full inline-flex justify-center items-center rounded-md bg-pink-400 hover:bg-pink-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all">
                                            {{ __('บันทึกข้อมูลที่นั่ง') }}
                                        </button>
                                    </form>

                                    <form :action="'{{ url('/admin/seats') }}/' + selectedSeat.id" method="POST" @submit="if (!confirm('ต้องการลบที่นั่งนี้หรือไม่?')) $event.preventDefault()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full rounded-md border border-red-300 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">{{ __('ลบที่นั่ง') }}</button>
                                    </form>
                                </div>
                                <p x-show="selectedSeat.booked" class="text-sm text-gray-600">{{ __('ที่นั่งนี้มีรายการจอง จึงไม่สามารถแก้ไขหรือลบได้') }}</p>
                            </div>
                        </template>

                        <!-- แสดงเมื่อยังไม่ได้เลือกที่นั่ง -->
                        <template x-if="!selectedSeat">
                            <div class="text-center py-12 text-gray-400 text-sm">
                                {{ __('กรุณาคลิกเลือกที่นั่งจากแผนผังด้านซ้ายเพื่อจัดการข้อมูล') }}
                            </div>
                        </template>
                    </div>
                </aside>

                <!-- กล่องคำอธิบายสี (แสดงอยู่ใต้ aside) -->
<!-- กล่องคำอธิบายสี (แสดงอยู่ใต้ aside) -->
<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
    <h4 class="mb-3 text-sm font-bold text-gray-800 border-b border-gray-100 pb-2">{{ __('คำอธิบายสีที่นั่ง') }}</h4>
    <div class="space-y-3 text-xs text-gray-600">
        <div class="flex items-center gap-3">
            <span class="inline-block h-4 w-4 shrink-0 rounded" style="background-color: #fbbf24; border: 1px solid #d97706; min-width: 1rem; min-height: 1rem;"></span>
            <span>{{ __('กำลังเลือก (Selected)') }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-block h-4 w-4 shrink-0 rounded" style="background-color: #9333ea; min-width: 1rem; min-height: 1rem;"></span>
            <span>{{ __('VIP / 350 บาท (สีม่วง)') }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-block h-4 w-4 shrink-0 rounded" style="background-color: #2563eb; min-width: 1rem; min-height: 1rem;"></span>
            <span>{{ __('Premium / 240 บาท (สีฟ้า)') }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-block h-4 w-4 shrink-0 rounded" style="background-color: #047857; min-width: 1rem; min-height: 1rem;"></span>
            <span>{{ __('Deluxe / 180 บาท (สีเขียว)') }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-block h-4 w-4 shrink-0 rounded" style="background-color: #6b7280; min-width: 1rem; min-height: 1rem;"></span>
            <span>{{ __('มีการจองแล้ว (สีเทา)') }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex h-4 w-4 shrink-0 rounded bg-white border border-dashed border-gray-400 items-center justify-center text-gray-500 font-bold" style="font-size: 10px; min-width: 1rem; min-height: 1rem;">+</span>
            <span>{{ __('ช่องว่าง (คลิกเพื่อเพิ่ม)') }}</span>
        </div>
    </div>
</div>
            </div>

        </div>
    </div>
        </div>
    </section>
@endsection