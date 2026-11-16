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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->integer('level')->default(1); // Nivel de competencia (1-5)
            $table->decimal('experience_points', 8, 2)->default(0); // Puntos de experiencia
            $table->timestamp('last_updated')->useCurrent(); // Última actualización
            $table->timestamps();

            $table->unique(['user_id', 'skill_id']); // Un usuario no puede tener la misma habilidad duplicada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
