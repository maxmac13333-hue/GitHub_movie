@extends('layouts.app')
@section('title', 'รายการจองทั้งหมด')
@section('header')<h1 class="text-2xl font-bold">รายการจองทั้งหมด</h1>@endsection
@section('content')
    <div class="mx-auto max-w-6xl space-y-4 px-4 py-10">
        @forelse($bookings as $booking)
            <a
                href="{{ route('bookings.show', $booking) }}"
                class="cinema-panel block p-5 hover:border-amber-400/50"
            >
                <div class="flex flex-wrap justify-between gap-3">
                    <div>
                        <p class="text-sm text-amber-300">#{{ $booking->Booking_ID }} · {{ $booking->payment_label }}</p>
                        <h2 class="font-bold">{{ $booking->showtime->movie->Title }}</h2>
                        <p class="text-sm text-slate-400">{{ $booking->user->name ?? 'ไม่พบผู้ใช้' }} ·
                            {{ $booking->showtime->Show_Date }} {{ substr($booking->showtime->Show_Time, 0, 5) }} ·
                            {{ $booking->showtime->theater->Theater_Name }}</p>
                        <p class="text-sm text-slate-400">ที่นั่ง {{ $booking->Seat_Summary ?: '—' }}</p>
                    </div><strong>{{ number_format($booking->Total_Price) }} บาท</strong>
                </div>
            </a>
        @empty <div class="cinema-panel p-8">ยังไม่มีรายการจอง</div>
        @endforelse {{ $bookings->links() }}
    </div>
@endsection
