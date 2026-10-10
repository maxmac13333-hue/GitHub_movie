<?php

namespace Database\Seeders;

use App\Models\{Movie, Seat, Showtime, Theater};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $catalog = json_decode(file_get_contents(database_path('data/movies.json')), true, 512, JSON_THROW_ON_ERROR);
            $movies = collect($catalog)->map(function ($data) {
                unset($data['page'], $data['Poster_Source']);
                return Movie::updateOrCreate(['Title' => $data['Title']], $data);
            });
                        $this->call(FFCinemaTheatersSeeder::class);
            $halls = Theater::whereBetween('Theater_ID', [1,8])->orderBy('Theater_ID')->get();
foreach (range(0, 6) as $day) {
                $date = today()->addDays($day)->toDateString();
                foreach ($halls as $hallIndex => $hall) {
                    foreach (['10:00:00', '14:00:00', '18:00:00', '22:00:00'] as $slot => $time) {
                        $movie = $movies[($hallIndex * 4 + $slot + $day) % $movies->count()];
                        $start = strtotime($date.' '.$time);
                        $end = $start + ($movie->Duration + 20) * 60;
                        $conflict = Showtime::with('movie')->where('Theater_ID', $hall->Theater_ID)->whereBetween('Show_Date', [today()->addDays($day - 1)->toDateString(), today()->addDays($day + 1)->toDateString()])->get()
                            ->contains(function ($show) use ($start, $end) {
                                $other = strtotime($show->Show_Date.' '.$show->Show_Time);
                                return $start < $other + (($show->movie->Duration ?: 120) + 20) * 60 && $other < $end;
                            });
                        if (!$conflict) Showtime::create(['Movie_ID' => $movie->Movie_ID, 'Theater_ID' => $hall->Theater_ID,
                            'Show_Date' => $date, 'Show_Time' => $time, 'Language' => $hallIndex % 2 === 0 ? 'เสียงไทย' : 'เสียงอังกฤษ · บรรยายไทย']);
                    }
                }
            }
        });
    }
}

