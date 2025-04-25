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
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requesting_user_id')->constrained('users');
            $table->foreignId('doctor_id')->nullable()->constrained('users');
            $table->foreignId('visit_id')->constrained('visits');
            $table->string('status')->default('pending'); // pending, accepted, completed, cancelled
            $table->text('reason');
            $table->text('notes')->nullable();
            $table->text('response')->nullable();
            $table->dateTime('requested_at');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};
