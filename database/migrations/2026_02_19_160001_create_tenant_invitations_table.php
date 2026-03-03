<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tenant_id')->index();
            $table->string('email');
            $table->string('token')->unique();

            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])
                ->default('pending')
                ->index();

            $table->ulid('invited_by_user_id')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();

            // FK
            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('invited_by_user_id')
                ->references('id')->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Índice compuesto para evitar invitaciones duplicadas pendientes
            $table->unique(['tenant_id', 'email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_invitations');
    }
};
