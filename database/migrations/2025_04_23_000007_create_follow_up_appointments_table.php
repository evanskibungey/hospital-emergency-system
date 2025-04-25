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
        Schema::create('follow_up_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('visit_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('discharge_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('scheduled_by')->constrained('users');
            $table->foreignId('doctor_id')->nullable()->constrained('users');
            $table->string('specialty')->nullable();
            $table->string('department')->nullable();
            $table->text('reason_for_visit');
            $table->timestamp('appointment_time');
            $table->integer('estimated_duration_minutes')->default(30);
            $table->boolean('is_urgent')->default(false);
            $table->text('special_instructions')->nullable();
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_appointments');
    }
};
