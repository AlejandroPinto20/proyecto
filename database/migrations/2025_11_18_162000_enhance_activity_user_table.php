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
            // Add new columns for better tracking
            $table->timestamp('completed_at')->nullable()->after('joined_at');
            $table->integer('points_earned')->default(0)->after('completed_at');
            $table->text('completion_notes')->nullable()->after('points_earned');
            $table->decimal('attendance_percentage', 5, 2)->default(100.00)->after('completion_notes');
            $table->json('skill_improvements')->nullable()->after('attendance_percentage');
            
            // Add indexes for better performance
            $table->index(['status', 'joined_at']);
            $table->index(['completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_user', function (Blueprint $table) {
            $table->dropColumn([
                'completed_at',
                'points_earned',
                'completion_notes',
                'attendance_percentage',
                'skill_improvements'
            ]);
            
            $table->dropIndex(['status', 'joined_at']);
            $table->dropIndex(['completed_at']);
        });
    }
};