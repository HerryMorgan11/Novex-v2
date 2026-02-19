<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_memberships', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->ulid('user_id')->unique();   // 1 usuario -> 1 tenant (regla tuya)
            $table->string('tenant_id')->index();

            $table->boolean('is_owner')->default(false);

            $table->enum('status', ['active', 'invited', 'disabled'])
                ->default('active')
                ->index();

            $table->timestamp('joined_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_memberships');
    }
};
