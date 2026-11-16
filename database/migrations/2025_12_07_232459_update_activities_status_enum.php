<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero cambiar a VARCHAR para poder actualizar
        DB::statement('ALTER TABLE activities MODIFY status VARCHAR(50)');
        
        // Mapear estados antiguos a nuevos
        DB::table('activities')->where('status', 'active')->update(['status' => 'abierta']);
        DB::table('activities')->where('status', 'pending')->update(['status' => 'proxima']);
        DB::table('activities')->where('status', 'inactive')->update(['status' => 'proxima']);
        DB::table('activities')->where('status', 'completed')->update(['status' => 'completada']);
        DB::table('activities')->where('status', 'cancelled')->update(['status' => 'cancelada']);
        
        // Ahora cambiar a ENUM con los nuevos valores
        DB::statement("ALTER TABLE activities MODIFY status ENUM('abierta', 'proxima', 'en_progreso', 'completada', 'cancelada') DEFAULT 'abierta'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a estados antiguos
        DB::table('activities')->where('status', 'abierta')->update(['status' => 'active']);
        DB::table('activities')->where('status', 'proxima')->update(['status' => 'pending']);
        DB::table('activities')->where('status', 'en_progreso')->update(['status' => 'active']);
        DB::table('activities')->where('status', 'completada')->update(['status' => 'completed']);
        DB::table('activities')->where('status', 'cancelada')->update(['status' => 'cancelled']);
        
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'pending', 'completed', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }
};
