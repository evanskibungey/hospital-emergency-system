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
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('treatment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ordered_by')->constrained('users');
            $table->string('test_name');
            $table->text('test_details')->nullable();
            $table->text('reason_for_test')->nullable();
            $table->boolean('is_stat')->default(false);
            $table->enum('status', ['ordered', 'collected', 'in_progress', 'completed', 'cancelled'])->default('ordered');
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('result_summary')->nullable();
            $table->text('result_details')->nullable();
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
        Schema::dropIfExists('lab_orders');
    }
};
