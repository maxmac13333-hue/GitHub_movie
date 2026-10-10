<?php
namespace Tests\Feature;
use App\Models\{Booking,Movie,Showtime,User,Ticket,Seat};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class DemoPaymentTest extends TestCase {
 use RefreshDatabase;
 private function reserve(): Booking {
  $user=User::factory()->create();$movie=Movie::create(['Title'=>'Payment test','Duration'=>120]);
  $show=Showtime::create(['Movie_ID'=>$movie->Movie_ID,'Theater_ID'=>1,'Show_Date'=>today()->addDay()->toDateString(),'Show_Time'=>'18:00','Language'=>'Thai']);
  $this->actingAs($user)->post(route('bookings.store',$show),['seats'=>[Seat::where('Theater_ID',1)->first()->Seat_ID]])->assertSessionHasNoErrors();
  return Booking::firstOrFail();
 }
 public function test_demo_payment_upload_and_private_slip(): void {
  Storage::fake('local');$booking=$this->reserve();
  $this->assertSame('pending',$booking->payment_state);
  $this->get(route('bookings.show',$booking))->assertOk()->assertSee('ไม่ต้องโอนเงินจริง')->assertDontSee($booking->tickets->first()->Ticket_Code);
  $this->post(route('bookings.pay',$booking),['slip'=>UploadedFile::fake()->create('bad.txt',1)])->assertSessionHasErrors('slip');
  $this->post(route('bookings.pay',$booking),['slip'=>UploadedFile::fake()->image('demo.png')])->assertSessionHasNoErrors();
  $booking->refresh();$this->assertSame('paid',$booking->payment_state);Storage::disk('local')->assertExists($booking->payment_slip);
  $this->get(route('bookings.show',$booking))->assertOk()->assertSee($booking->tickets->first()->Ticket_Code);
  $this->get(route('bookings.slip',$booking))->assertOk();
  $this->actingAs(User::factory()->create())->get(route('bookings.slip',$booking))->assertForbidden();
 }
 public function test_expiration_releases_seats_and_rejects_late_payment(): void {
  Storage::fake('local');$booking=$this->reserve();$show=$booking->showtime;$seat=$booking->tickets->first()->Seat_ID;
  $this->travel(10)->minutes();
  $this->post(route('bookings.pay',$booking),['slip'=>UploadedFile::fake()->image('late.png')])->assertSessionHasErrors('slip');
  $this->assertSame('expired',$booking->fresh()->payment_state);$this->assertDatabaseCount('ticket',0);
  $this->post(route('bookings.store',$show),['seats'=>[$seat]])->assertSessionHasNoErrors();
  $this->assertDatabaseCount('ticket',1);
 }
}
