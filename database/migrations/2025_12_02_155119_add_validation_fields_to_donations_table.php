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
        Schema::table('donations', function (Blueprint $table) {
            $table->string('verification_code')->nullable()->after('bank_account_info');
            $table->timestamp('verified_at')->nullable()->after('verification_code');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
            $table->text('verification_notes')->nullable()->after('verified_by');
            $table->enum('validation_status', ['pending', 'verifying', 'approved', 'rejected'])->default('pending')->after('verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'verification_code',
                'verified_at', 
                'verified_by',
                'verification_notes',
                'validation_status'
            ]);
        });
    }
};