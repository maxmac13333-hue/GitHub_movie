<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\TheaterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route(auth()->user()?->is_admin ? 'admin.movies' : 'movies.index');
});

Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}/showtimes', [MovieController::class, 'showtimes'])
    ->name('movies.showtimes');
Route::get('/showtimes/{showtime}/availability', [MovieController::class, 'availability'])->middleware('auth')->name('showtimes.availability');
Route::get('/showtimes/{showtime}/seats', [MovieController::class, 'selectSeats'])
    ->middleware(['auth', 'verified'])
    ->name('showtimes.seats');

Route::get('/dashboard', [MovieController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/showtimes/{showtime}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
    Route::post('/showtimes/{showtime}/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/booking-history', [BookingController::class, 'index'])->name('booking-history.index');
    Route::post('/bookings/{booking}/payment', [BookingController::class, 'pay'])->name('bookings.pay');
    Route::get('/bookings/{booking}/slip', [BookingController::class, 'slip'])->name('bookings.slip');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/movies', [CatalogController::class, 'movies'])->name('movies');
    Route::post('/movies', [CatalogController::class, 'movieStore'])->name('movies.store');
    Route::put('/movies/{movie}', [CatalogController::class, 'movieUpdate'])->name('movies.update');
    Route::delete('/movies/{movie}', [CatalogController::class, 'movieDestroy'])->name('movies.destroy');
    Route::get('/showtimes', [CatalogController::class, 'showtimes'])->name('showtimes');
    Route::post('/showtimes', [CatalogController::class, 'showtimeStore'])->name('showtimes.store');
    Route::put('/showtimes/{showtime}', [CatalogController::class, 'showtimeUpdate'])->name('showtimes.update');
    Route::delete('/showtimes/{showtime}', [CatalogController::class, 'showtimeDestroy'])->name('showtimes.destroy');
    Route::get('/bookings', [CatalogController::class, 'bookings'])->name('bookings');
    Route::get('/users', [CatalogController::class, 'users'])->name('users');
    Route::get('/theaters', [TheaterController::class, 'index'])->name('theaters');
    Route::post('/theaters', [TheaterController::class, 'store'])->name('theaters.store');
    Route::put('/theaters/{theater}', [TheaterController::class, 'update'])->name('theaters.update');
    Route::get('/theaters/{theater}/edit', [TheaterController::class, 'edit'])->name('theaters.edit');
    Route::post('/theaters/{theater}/seats', [SeatController::class, 'store'])->name('seats.store');
    Route::put('/seats/{seat}', [SeatController::class, 'update'])->name('seats.update');
    Route::delete('/seats/{seat}', [SeatController::class, 'destroy'])->name('seats.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

