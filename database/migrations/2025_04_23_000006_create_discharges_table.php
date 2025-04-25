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
        Schema::create('discharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('discharged_by')->constrained('users');
            $table->text('discharge_diagnosis');
            $table->text('discharge_summary');
            $table->text('discharge_instructions');
            $table->text('medications_at_discharge')->nullable();
            $table->text('activity_restrictions')->nullable();
            $table->text('diet_instructions')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->enum('discharge_disposition', [
                'home', 
                'home_with_services', 
                'transfer_to_facility', 
                'left_against_medical_advice',
                'other'
            ])->default('home');
            $table->string('destination_facility')->nullable();
            $table->timestamp('discharged_at');
            $table->boolean('instructions_provided')->default(false);
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
        Schema::dropIfExists('discharges');
    }
};
