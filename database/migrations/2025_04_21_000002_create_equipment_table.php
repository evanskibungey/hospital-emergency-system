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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->nullable()->unique();
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->enum('type', ['portable', 'fixed', 'disposable', 'reusable'])->default('reusable');
            $table->enum('category', [
                'diagnostic', 'therapeutic', 'monitoring', 'laboratory',
                'surgical', 'emergency', 'life_support', 'patient_care',
                'administrative', 'other'
            ])->default('other');
            $table->integer('quantity')->default(1);
            $table->integer('available_quantity')->default(1);
            $table->date('purchase_date')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};