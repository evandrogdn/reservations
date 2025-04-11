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
            $table->dateTime('reservation_start')->unique();
            $table->dateTime('reservation_end')->unique();
            $table->timestamps();
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
