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
            $table->enum('payment_method', ['cash', 'bank_transfer', 'qr_code', 'online_payment', 'other'])->nullable()->after('notes');
            $table->text('qr_code_data')->nullable()->after('payment_method');
            $table->text('bank_account_info')->nullable()->after('qr_code_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'qr_code_data', 'bank_account_info']);
        });
    }
};
