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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la habilidad
            $table->string('slug')->unique(); // Slug para URLs
            $table->text('description')->nullable(); // Descripción de la habilidad
            $table->string('category')->nullable(); // Categoría (técnica, social, etc.)
            $table->boolean('is_active')->default(true); // Si está activa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
