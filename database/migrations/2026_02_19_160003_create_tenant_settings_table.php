<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tenant_id')->unique()->index();

            // Configuración básica
            $table->string('timezone')->default('UTC');
            $table->string('locale')->default('es');

            // Límites y cuotas
            $table->integer('max_users')->nullable();
            $table->integer('max_storage_gb')->nullable();

            // Features
            $table->json('enabled_features')->nullable();

            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
