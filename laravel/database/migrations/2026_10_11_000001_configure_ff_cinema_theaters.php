<?php
use Illuminate\Database\Migrations\Migration;
return new class extends Migration {
 public function up(): void { (new \Database\Seeders\FFCinemaTheatersSeeder)->run(); }
 public function down(): void { /* Preserve renamed halls, existing seats and booking history. */ }
};
