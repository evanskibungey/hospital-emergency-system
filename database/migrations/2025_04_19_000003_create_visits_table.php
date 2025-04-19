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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('registered_by')->constrained('users');
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['waiting', 'in_progress', 'treated', 'discharged'])->default('waiting');
            $table->string('chief_complaint');
            $table->text('initial_assessment')->nullable();
            $table->text('notes')->nullable();
            $table->string('department')->nullable();
            $table->integer('estimated_wait_time')->nullable(); // in minutes
            $table->integer('bed_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};