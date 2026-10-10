<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Theater;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CinemaBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_one_seat_can_only_be_sold_once_per_show_and_can_be_rebooked_after_cancellation(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $movie = Movie::create(['Title' => 'Test Movie', 'Duration' => 120]);
        $theater = Theater::create(['Theater_Name' => 'Hall 1', 'Theater_Location' => 'Bangkok']);
        $seat = Seat::create(['Theater_ID' => $theater->Theater_ID, 'seat_row' => 'A', 'seat_number' => 1,
            'Seat_No' => 'A1', 'Seat_Type' => 'Premium - 240 บาท', 'pos_x' => 1, 'pos_y' => 1]);
        $showtime = Showtime::create(['Movie_ID' => $movie->Movie_ID, 'Theater_ID' => $theater->Theater_ID,
            'Show_Date' => today()->addDay()->toDateString(), 'Show_Time' => '18:00', 'Language' => 'Thai']);

        $this->actingAs($user)->post(route('bookings.checkout', $showtime), ['seats' => [$seat->Seat_ID]])
            ->assertOk()->assertSee('240');

        $this->actingAs($user)->post(route('bookings.store', $showtime), ['seats' => [$seat->Seat_ID]])
            ->assertRedirect();
        $this->assertDatabaseHas('booking', ['User_ID' => $user->id, 'Total_Price' => 240]);
        $this->assertDatabaseHas('ticket', ['Show_ID' => $showtime->Show_ID, 'Seat_ID' => $seat->Seat_ID]);

        $this->actingAs($other)->post(route('bookings.store', $showtime), ['seats' => [$seat->Seat_ID]])
            ->assertSessionHasErrors('seats');
        $this->assertDatabaseCount('booking', 1);

        $bookingId = \App\Models\Booking::firstOrFail()->Booking_ID;
        $this->actingAs($other)->get(route('bookings.show', $bookingId))->assertForbidden();
        $this->actingAs($user)->post(route('bookings.cancel', $bookingId))->assertRedirect();
        $this->assertDatabaseCount('ticket', 0);
        $this->assertDatabaseHas('booking', ['Booking_ID' => $bookingId, 'Status' => 'ยกเลิก', 'Seat_Summary' => 'A1']);
        $this->actingAs($other)->post(route('bookings.store', $showtime), ['seats' => [$seat->Seat_ID]])
            ->assertRedirect();
        $this->assertDatabaseCount('booking', 2);
    }
}
