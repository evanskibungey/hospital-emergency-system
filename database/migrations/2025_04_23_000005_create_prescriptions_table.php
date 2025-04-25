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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('treatment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('prescribed_by')->constrained('users');
            $table->foreignId('medication_id')->nullable()->constrained();
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('route');
            $table->text('instructions');
            $table->integer('quantity');
            $table->integer('refills')->default(0);
            $table->boolean('is_controlled_substance')->default(false);
            $table->enum('status', ['active', 'completed', 'cancelled', 'on_hold'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
