<?php

namespace Tests\Feature;

use App\Models\{Movie, Seat, Showtime, Theater, User};
use Database\Seeders\CinemaCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CinemaCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_is_persisted_searchable_and_seeding_is_repeatable(): void
    {
        $this->seed(CinemaCatalogSeeder::class);
        $this->assertDatabaseCount('movie', 10);
        $this->assertDatabaseCount('seat', 1772);
        $this->assertDatabaseCount('showtime', 224);
        $this->get('/movies?q=Interstellar')->assertOk()->assertSee('Interstellar')->assertDontSee('Dune: Part Two');
        $movie = Movie::where('Title', 'Dune: Part Two')->firstOrFail();
        $this->get('/movies?q='.urlencode($movie->Thai_Title))->assertOk()->assertSee('Dune: Part Two')->assertDontSee('Interstellar');
        $this->get('/movies?q=does-not-exist')->assertOk()->assertDontSee('Interstellar');
        $this->seed(CinemaCatalogSeeder::class);
        $this->assertDatabaseCount('movie', 10);
        $this->assertDatabaseCount('seat', 1772);
        $this->assertDatabaseCount('showtime', 224);

        foreach (Theater::all() as $theater) {
            $shows = Showtime::with('movie')->where('Theater_ID', $theater->Theater_ID)->orderBy('Show_Date')->orderBy('Show_Time')->get();
            $previousEnd = 0;
            foreach ($shows as $show) {
                $start = strtotime($show->Show_Date.' '.$show->Show_Time);
                $this->assertGreaterThanOrEqual($previousEnd, $start);
                $previousEnd = $start + ($show->movie->Duration + 20) * 60;
            }
        }
        $show = Showtime::where('Show_Date', today()->addDay()->toDateString())->firstOrFail();
        $this->actingAs(User::factory()->create())->get(route('showtimes.seats', $show))->assertOk()->assertSee('ที่นั่ง L18');
        $this->get(route('showtimes.availability', $show))->assertOk()->assertExactJson(['taken' => []]);
    }
}

