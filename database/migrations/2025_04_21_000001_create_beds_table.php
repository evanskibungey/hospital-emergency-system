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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_number');
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'occupied', 'cleaning', 'maintenance', 'reserved'])->default('available');
            $table->enum('type', ['regular', 'icu', 'pediatric', 'maternity', 'isolation'])->default('regular');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add a unique constraint on bed_number + location
            $table->unique(['bed_number', 'location']);
        });
        
        // Add a bed_id column to the visits table to track bed assignments
        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('bed_id')->nullable()->after('assigned_to')->constrained()->nullOnDelete();
            $table->dateTime('bed_assigned_at')->nullable()->after('bed_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['bed_id']);
            $table->dropColumn(['bed_id', 'bed_assigned_at']);
        });
        
        Schema::dropIfExists('beds');
    }
};
