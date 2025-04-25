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
        Schema::create('equipment_checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('checked_out_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('checked_out_at');
            $table->dateTime('expected_return_at')->nullable();
            $table->dateTime('checked_in_at')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('purpose')->nullable();
            $table->enum('status', ['checked_out', 'checked_in', 'overdue', 'lost'])->default('checked_out');
            $table->text('checkout_notes')->nullable();
            $table->text('checkin_notes')->nullable();
            $table->text('condition_at_checkout')->nullable();
            $table->text('condition_at_checkin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_checkouts');
    }
};