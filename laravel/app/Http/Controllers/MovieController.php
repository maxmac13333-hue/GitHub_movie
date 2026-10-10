<?php

namespace App\Http\Controllers;

use App\Models\{Movie, Showtime, Ticket};

use Illuminate\Http\Request;

class MovieController extends Controller
{
    private function future($query): void
    {
        $query->where(fn ($q) => $q->where('Show_Date', '>', today()->toDateString())
            ->orWhere(fn ($q) => $q->where('Show_Date', today()->toDateString())->where('Show_Time', '>', now()->format('H:i:s'))));
    }

    public function dashboard(Request $request)
    {
        if ($request->user()->is_admin) return redirect()->route('admin.movies');
        return $this->catalog($request);
    }
    public function index(Request $request) { return $this->catalog($request); }

    private function catalog(Request $request)
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'genre' => ['nullable', 'string', 'max:100'], 'date' => ['nullable', 'date_format:Y-m-d']]);
        $query = Movie::query()->with(['showtimes' => function ($q) use ($filters) {
            $this->future($q);
            $q->when($filters['date'] ?? null, fn ($q, $date) => $q->where('Show_Date', $date))->orderBy('Show_Date')->orderBy('Show_Time');
        }]);
        if ($search = trim($filters['q'] ?? '')) {
            $query->where(fn ($q) => $q->where('Title', 'like', '%'.$search.'%')->orWhere('Thai_Title', 'like', '%'.$search.'%')->orWhere('Director', 'like', '%'.$search.'%'));
        }
        $query->when($filters['genre'] ?? null, fn ($q, $genre) => $q->where('Genre', 'like', '%'.$genre.'%'));
        if ($date = $filters['date'] ?? null) $query->whereHas('showtimes', function ($q) use ($date) { $this->future($q); $q->where('Show_Date', $date); });
        $movies = $query->orderBy('Movie_ID')->get();
        $genres = Movie::pluck('Genre')->flatMap(fn ($genre) => explode('/', $genre ?? ''))->map(fn ($genre) => trim($genre))->filter()->unique()->values();
        $featured = Movie::where('Title', 'Dune: Part Two')->first() ?? Movie::first();
        $spotlights = Movie::whereIn('Title', ['Interstellar', 'Inside Out 2'])->get();
        return view('movies', compact('movies', 'genres', 'featured', 'spotlights', 'filters'));
    }

    public function showtimes(Request $request, int $movie)
    {
        $filters = $request->validate(['date' => ['nullable', 'date_format:Y-m-d']]);
        $movie = Movie::findOrFail($movie);
        $shows = $movie->showtimes()->with('theater')->withCount('tickets');
        $this->future($shows->getQuery());
        $showtimes = $shows->orderBy('Show_Date')->orderBy('Show_Time')->get();
        $dates = $showtimes->pluck('Show_Date')->unique()->values();
        $selectedDate = $filters['date'] ?? $dates->first() ?? today()->toDateString();
        $groups = $showtimes->where('Show_Date', $selectedDate)->groupBy('Theater_ID');
        $showtimes->load('theater.seats');
        return view('showtimes', compact('movie', 'dates', 'selectedDate', 'groups'));
    }

    public function selectSeats(int $showtime)
    {
        $showtime = Showtime::with('movie', 'theater.seats')->findOrFail($showtime);
        $takenSeatIds = $this->taken($showtime);
        $seats = $showtime->theater->seats;
        return view('select-seats', [
            'movie' => $showtime->movie, 'showtime' => $showtime, 'seats' => $seats,
            'takenSeatIds' => $takenSeatIds,
            'columnCount' => max(8, (int) $seats->max('pos_x')),
            'rowCount' => max(1, (int) $seats->max('pos_y')),
        ]);
    }

    private function taken(Showtime $showtime): array
    {
        return Ticket::where('Show_ID', $showtime->Show_ID)
            ->whereHas('booking', fn ($q) => $q->where('Status', '!=', 'ยกเลิก'))
            ->pluck('Seat_ID')->map(fn ($id) => (int) $id)->all();
    }

    public function availability(Showtime $showtime)
    {
        return response()->json(['taken' => $this->taken($showtime)])->header('Cache-Control', 'no-store');
    }
}


