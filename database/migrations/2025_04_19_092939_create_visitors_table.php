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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('relationship_to_patient');
            $table->string('id_type')->nullable(); // e.g., driver's license, passport
            $table->string('id_number')->nullable();
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();
            $table->string('pass_number')->unique();
            $table->boolean('is_active')->default(true);
            $table->foreignId('registered_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};