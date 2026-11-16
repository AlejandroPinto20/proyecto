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
        Schema::create('skill_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_skill_id')->constrained('skills')->onDelete('cascade'); // Habilidad anterior
            $table->foreignId('to_skill_id')->constrained('skills')->onDelete('cascade'); // Habilidad desarrollada
            $table->integer('from_level')->default(1); // Nivel anterior
            $table->integer('to_level')->default(1); // Nivel nuevo
            $table->decimal('experience_gained', 8, 2)->default(0); // Experiencia ganada
            $table->timestamp('transition_date'); // Fecha de la transición
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_transitions');
    }
};
