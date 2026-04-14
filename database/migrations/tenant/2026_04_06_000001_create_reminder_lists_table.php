<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_lists', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 26)->index(); // ULID del usuario (central)
            $table->string('name');
            $table->string('color', 7)->nullable();  // Hex: #FF5733
            $table->string('icon', 50)->nullable();  // star, bell, flag, etc.
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_lists');
    }
};
