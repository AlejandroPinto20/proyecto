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
        Schema::table('activity_user', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'joined', 'in_progress', 'completed', 'dropped'])->default('pending');
            $table->timestamp('joined_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_user', function (Blueprint $table) {
            $table->dropColumn(['status', 'joined_at']);
        });
    }
};
