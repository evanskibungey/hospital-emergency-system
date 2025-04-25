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
        Schema::create('imaging_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('treatment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ordered_by')->constrained('users');
            $table->string('imaging_type');
            $table->string('body_part');
            $table->text('clinical_information')->nullable();
            $table->text('reason_for_exam')->nullable();
            $table->boolean('is_stat')->default(false);
            $table->boolean('requires_contrast')->default(false);
            $table->enum('status', ['ordered', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('ordered');
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('findings')->nullable();
            $table->text('impression')->nullable();
            $table->foreignId('radiologist_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('imaging_orders');
    }
};
