<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Theater;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CatalogController extends Controller
{
    public function movies(Request $request) {
        $q = $request->validate(['q' => ['nullable', 'string', 'max:200']])['q'] ?? '';
        return view('admin.admin_movies', ['movies' => Movie::withCount('showtimes')->when($q, fn($query) => $query->where(fn($query) => $query->where('Title', 'like', '%'.$q.'%')->orWhere('Thai_Title', 'like', '%'.$q.'%')))->orderByDesc('Movie_ID')->get()]);
    }
    public function movieStore(Request $request) { Movie::create($this->movieData($request)); return back()->with('status', 'เพิ่มภาพยนตร์แล้ว'); }
    public function movieUpdate(Request $request, Movie $movie) { $movie->update($this->movieData($request)); return back()->with('status', 'บันทึกภาพยนตร์แล้ว'); }
    public function movieDestroy(Movie $movie) {
        if ($movie->showtimes()->exists()) return back()->withErrors(['movie' => 'ลบหนังที่มีรอบฉายไม่ได้']);
        $movie->delete(); return back()->with('status', 'ลบภาพยนตร์แล้ว');
    }
    private function movieData(Request $request): array { $data = $request->validate([
        'Title' => ['required', 'string', 'max:200'], 'Thai_Title' => ['nullable', 'string', 'max:200'], 'Release_Date' => ['nullable', 'date'],
        'Duration' => ['nullable', 'integer', 'min:1'], 'Genre' => ['nullable', 'string', 'max:100'],
        'Director' => ['nullable', 'string', 'max:150'], 'Actors' => ['nullable', 'string'],
        'Synopsis' => ['nullable', 'string'],
        'poster_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ], ['poster_file.image' => 'กรุณาเลือกไฟล์รูปภาพ', 'poster_file.mimes' => 'รองรับไฟล์ JPG, PNG และ WebP', 'poster_file.max' => 'รูปภาพต้องมีขนาดไม่เกิน 2 MB']);
        unset($data['poster_file']);
        if ($request->hasFile('poster_file')) {
            $data['Poster'] = $request->file('poster_file')->store('uploads', 'posters');
        }
        return $data;
    }
    public function showtimes(Request $request) {
        $filters = $request->validate(['date' => ['nullable','date'], 'movie' => ['nullable','integer'], 'theater' => ['nullable','integer']]);
        return view('admin.admin_showtimes', [
        'showtimes' => Showtime::with(['movie', 'theater'])->when($filters['date'] ?? null, fn($q,$v) => $q->where('Show_Date',$v))->when($filters['movie'] ?? null, fn($q,$v) => $q->where('Movie_ID',$v))->when($filters['theater'] ?? null, fn($q,$v) => $q->where('Theater_ID',$v))->orderByDesc('Show_Date')->orderBy('Show_Time')->paginate(12)->withQueryString(),
        'movies' => Movie::orderBy('Title')->get(), 'theaters' => Theater::orderBy('Theater_Name')->get(),
    ]); }
    public function showtimeStore(Request $request) { Showtime::create($this->showtimeData($request)); return back()->with('status', 'เพิ่มรอบฉายแล้ว'); }
    public function showtimeUpdate(Request $request, Showtime $showtime) {
        if ($showtime->bookings()->exists()) return back()->withErrors(['showtime' => 'แก้ไขรอบที่มีการจองไม่ได้']);
        $showtime->update($this->showtimeData($request, $showtime)); return back()->with('status', 'บันทึกรอบฉายแล้ว');
    }
    public function showtimeDestroy(Showtime $showtime) {
        if ($showtime->bookings()->exists()) return back()->withErrors(['showtime' => 'ลบรอบที่มีการจองไม่ได้']);
        $showtime->delete(); return back()->with('status', 'ลบรอบฉายแล้ว');
    }
    private function showtimeData(Request $request, ?Showtime $existing = null): array {
        $data = $request->validate([
            'Movie_ID' => ['required', 'exists:movie,Movie_ID'], 'Theater_ID' => ['required', 'exists:theater,Theater_ID'],
            'Show_Date' => ['required', 'date', 'after_or_equal:today'], 'Show_Time' => ['required', 'date_format:H:i'],
            'Language' => ['nullable', 'string', 'max:50'],
        ]);
        $movie = Movie::findOrFail($data['Movie_ID']);
        $start = strtotime($data['Show_Date'].' '.$data['Show_Time']);
        $end = $start + (($movie->Duration ?: 120) + 20) * 60;
        foreach (Showtime::with('movie')->where('Theater_ID', $data['Theater_ID'])->where('Show_Date', $data['Show_Date'])->get() as $other) {
            if ($existing && $other->Show_ID === $existing->Show_ID) continue;
            $otherStart = strtotime($other->Show_Date.' '.$other->Show_Time);
            $otherEnd = $otherStart + (($other->movie->Duration ?: 120) + 20) * 60;
            if ($start < $otherEnd && $otherStart < $end) throw ValidationException::withMessages(['Show_Time' => 'รอบฉายซ้อนกับรอบอื่นในโรงนี้']);
        }
        return $data;
    }
    public function bookings() { return view('admin.admin_bookings', ['bookings' => Booking::with(['user', 'showtime.movie', 'showtime.theater', 'tickets.seat'])->orderByDesc('Booking_Date')->paginate(20)]); }
    public function users() { return view('admin.admin_users', ['users' => User::withCount('bookings')->orderByDesc('id')->paginate(20)]); }
}
