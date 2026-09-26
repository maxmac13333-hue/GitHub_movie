<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function dashboard()
    {
        $movies = Movie::with('showtimes.theater')->get();

        return view('dashboard', compact('movies'));
    }

    public function index()
    {
        $movies = Movie::with('showtimes.theater')->get();

        return view('movies', compact('movies'));
    }

    public function showtimes(int $movie)
    {
        $movie = Movie::with('showtimes.theater')->findOrFail($movie);

        return view('showtimes', [
            'movie' => [
                'title' => $movie->Title,
                'image' => 'movies_png/' . ($movie->Poster ?: 'Avatar_The_Way_of_Water.png'),
                'genre' => $movie->Genre,
                'duration' => $movie->Duration . ' นาที',
            ],
            'showtimes' => $movie->showtimes,
        ]);
    }

    public function selectSeats(int $showtime)
    {
        $showtime = \App\Models\Showtime::with('movie', 'theater.seats')->findOrFail($showtime);

        return view('select-seats', [
            'movie' => $showtime->movie,
            'showtime' => $showtime,
            'seats' => $showtime->theater->seats,
        ]);
    }
}