<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
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
    return redirect()->route('login');
});

Route::get('/movies', [MovieController::class, 'index'])->middleware(['auth', 'verified'])->name('movies.index');
Route::get('/movies/{movie}/showtimes', [MovieController::class, 'showtimes'])
    ->middleware(['auth', 'verified'])
    ->name('movies.showtimes');
Route::get('/showtimes/{showtime}/seats', [MovieController::class, 'selectSeats'])
    ->middleware(['auth', 'verified'])
    ->name('showtimes.seats');

Route::get('/dashboard', [MovieController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/booking-history', 'booking-history')->middleware(['auth', 'verified'])->name('booking-history.index');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/movies', 'admin.admin_movies')->name('movies');
    Route::view('/bookings', 'admin.admin_bookings')->name('bookings');
    Route::view('/users', 'admin.admin_users')->name('users');
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
