<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega índices para optimizar queries en notas y recordatorios.
     */
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Índice para búsquedas por usuario
            $table->index('user_id');
            // Índice compuesto para búsquedas ordenadas
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('reminders', function (Blueprint $table) {
            // Índice compuesto para filtros comunes
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'status', 'due_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('reminders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'status', 'due_at']);
        });
    }
};
