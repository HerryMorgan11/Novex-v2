<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 26)->index();
            $table->string('name', 100);
            $table->string('color', 7)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'name']); // Un usuario no puede tener dos tags iguales
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
