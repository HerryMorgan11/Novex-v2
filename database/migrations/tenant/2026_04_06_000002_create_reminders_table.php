<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 26)->index();
            $table->foreignId('reminder_list_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->longText('notes')->nullable();
            $table->boolean('is_completed')->default(false)->index();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedTinyInteger('priority')->default(0); // 0=none,1=low,2=medium,3=high
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('due_at')->nullable()->index();
            $table->timestamp('remind_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('status', 20)->default('active')->index(); // active,archived
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
