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
        Schema::create('medication_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('medication_id')->constrained()->onDelete('cascade');
            $table->string('dosage'); // Amount to administer
            $table->enum('frequency', ['once', 'daily', 'twice_daily', 'three_times_daily', 'four_times_daily', 'as_needed', 'other']);
            $table->text('frequency_notes')->nullable(); // For custom frequencies
            $table->dateTime('scheduled_time');
            $table->enum('status', ['scheduled', 'administered', 'missed', 'cancelled'])->default('scheduled');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add soft delete to maintain medical record
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_schedules');
    }
};