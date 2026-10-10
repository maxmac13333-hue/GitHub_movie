<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('booking',function(Blueprint $table){$table->string('payment_state',20)->default('legacy');$table->dateTime('payment_expires_at')->nullable();$table->dateTime('paid_at')->nullable();$table->string('payment_slip')->nullable();$table->index(['payment_state','payment_expires_at']);}); }
 public function down(): void { Schema::table('booking',function(Blueprint $table){$table->dropIndex(['payment_state','payment_expires_at']);$table->dropColumn(['payment_state','payment_expires_at','paid_at','payment_slip']);}); }
};
