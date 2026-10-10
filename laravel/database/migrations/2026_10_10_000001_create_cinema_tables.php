<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', fn (Blueprint $table) => $table->boolean('is_admin')->default(false));
        }
        if (!Schema::hasTable('movie')) Schema::create('movie', function (Blueprint $table) {
            $table->increments('Movie_ID');
            $table->string('Title', 200);
            $table->date('Release_Date')->nullable();
            $table->unsignedInteger('Duration')->nullable();
            $table->string('Genre', 100)->nullable();
            $table->string('Director', 150)->nullable();
            $table->text('Actors')->nullable();
            $table->text('Synopsis')->nullable();
            $table->string('Poster')->nullable();
        });
        if (!Schema::hasTable('theater')) Schema::create('theater', function (Blueprint $table) {
            $table->increments('Theater_ID');
            $table->string('Theater_Location', 200);
            $table->string('Theater_Name', 150);
        });
        if (!Schema::hasTable('seat')) Schema::create('seat', function (Blueprint $table) {
            $table->increments('Seat_ID');
            $table->unsignedInteger('Theater_ID');
            $table->string('seat_row', 5);
            $table->unsignedInteger('seat_number');
            $table->string('Seat_No', 10);
            $table->string('Seat_Type', 100)->default('Deluxe - 180 บาท');
            $table->unsignedInteger('pos_x');
            $table->unsignedInteger('pos_y');
            $table->foreign('Theater_ID')->references('Theater_ID')->on('theater')->cascadeOnDelete();
            $table->unique(['Theater_ID', 'Seat_No']);
            $table->unique(['Theater_ID', 'pos_x', 'pos_y']);
        });
        if (!Schema::hasTable('showtime')) Schema::create('showtime', function (Blueprint $table) {
            $table->increments('Show_ID');
            $table->unsignedInteger('Movie_ID');
            $table->unsignedInteger('Theater_ID');
            $table->date('Show_Date');
            $table->time('Show_Time');
            $table->string('Language', 50)->nullable();
            $table->foreign('Movie_ID')->references('Movie_ID')->on('movie')->restrictOnDelete();
            $table->foreign('Theater_ID')->references('Theater_ID')->on('theater')->restrictOnDelete();
            $table->unique(['Theater_ID', 'Show_Date', 'Show_Time']);
        });
        if (!Schema::hasTable('booking')) Schema::create('booking', function (Blueprint $table) {
            $table->increments('Booking_ID');
            $table->foreignId('User_ID')->constrained('users')->restrictOnDelete();
            $table->unsignedInteger('Show_ID');
            $table->dateTime('Booking_Date')->useCurrent();
            $table->decimal('Total_Price', 10, 2);
            $table->string('Seat_Summary', 100)->nullable();
            $table->string('Status', 50)->default('ปกติ');
            $table->foreign('Show_ID')->references('Show_ID')->on('showtime')->restrictOnDelete();
        });
        if (!Schema::hasTable('ticket')) Schema::create('ticket', function (Blueprint $table) {
            $table->increments('Ticket_ID');
            $table->unsignedInteger('Booking_ID');
            $table->unsignedInteger('Show_ID');
            $table->unsignedInteger('Seat_ID');
            $table->string('Ticket_Code', 50)->unique();
            $table->foreign('Booking_ID')->references('Booking_ID')->on('booking')->cascadeOnDelete();
            $table->foreign('Show_ID')->references('Show_ID')->on('showtime')->restrictOnDelete();
            $table->foreign('Seat_ID')->references('Seat_ID')->on('seat')->restrictOnDelete();
            $table->unique(['Show_ID', 'Seat_ID']);
        });
        if (!Schema::hasColumn('ticket', 'Show_ID')) {
            Schema::table('ticket', fn (Blueprint $table) => $table->unsignedInteger('Show_ID')->nullable()->after('Booking_ID'));
            DB::table('ticket')->join('booking', 'ticket.Booking_ID', '=', 'booking.Booking_ID')->update(['ticket.Show_ID' => DB::raw('booking.Show_ID')]);
            Schema::table('ticket', fn (Blueprint $table) => $table->unique(['Show_ID', 'Seat_ID'], 'ticket_show_seat_unique'));
        }
        if (!Schema::hasColumn('booking', 'Seat_Summary')) {
            Schema::table('booking', fn (Blueprint $table) => $table->string('Seat_Summary', 100)->nullable());
            foreach (DB::table('booking')->get() as $booking) {
                $names = DB::table('ticket')->join('seat', 'ticket.Seat_ID', '=', 'seat.Seat_ID')
                    ->where('ticket.Booking_ID', $booking->Booking_ID)->orderBy('seat.Seat_No')->pluck('seat.Seat_No')->join(', ');
                DB::table('booking')->where('Booking_ID', $booking->Booking_ID)->update(['Seat_Summary' => $names ?: null]);
            }
        }
    }

    public function down(): void
    {
        // Imported databases may contain original data; never drop those tables on rollback.
    }
};
