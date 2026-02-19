<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_provisionings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tenant_id')->unique();

            $table->enum('status', ['pending', 'running', 'failed', 'completed'])
                ->default('pending')
                ->index();

            $table->string('step')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);

            $table->text('error_message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_provisionings');
    }
};
