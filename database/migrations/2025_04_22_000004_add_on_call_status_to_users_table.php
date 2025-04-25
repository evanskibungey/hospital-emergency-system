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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_on_call')->default(false)->after('remember_token');
            $table->timestamp('on_call_until')->nullable()->after('is_on_call');
            $table->string('specialty')->nullable()->after('on_call_until');
            $table->string('department')->nullable()->after('specialty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_on_call');
            $table->dropColumn('on_call_until');
            $table->dropColumn('specialty');
            $table->dropColumn('department');
        });
    }
};
