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
        Schema::create('equipment_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('requested_at');
            $table->dateTime('scheduled_for')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('type', ['preventive', 'corrective', 'inspection', 'calibration', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['requested', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->text('issue_description')->nullable();
            $table->text('work_performed')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('service_provider')->nullable();
            $table->string('contact_info')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenance');
    }
};