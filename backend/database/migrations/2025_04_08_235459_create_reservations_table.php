<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('phone_contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->integer('guests');
            $table->dateTime('reservation_start');
            $table->dateTime('reservation_end');
            $table->timestamps();

            $table->unique(['table_id', 'reservation_start', 'reservation_end'], 'unique_table_reservation');
            $table->index(['reservation_start', 'reservation_end'], 'index_reservation_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
