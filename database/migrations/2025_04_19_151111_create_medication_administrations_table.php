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
        Schema::create('medication_administrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('administered_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('administered_at');
            $table->string('actual_dosage')->nullable(); // In case actual dosage differs from scheduled
            $table->enum('status', ['completed', 'partial', 'refused', 'held', 'error'])->default('completed');
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
        Schema::dropIfExists('medication_administrations');
    }
};