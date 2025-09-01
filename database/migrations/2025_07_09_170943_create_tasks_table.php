<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domain\Task\TaskPriority;
use Src\Domain\Task\{TaskStatus};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade');
            $table->string('title', 100);
            $table->string('description', 500)->nullable();
            $table->enum('status', TaskStatus::toArray())->default(TaskStatus::NOT_STARTED->value);
            $table->enum('priority', TaskPriority::toArray())->default(TaskPriority::MEDIUM->value);
            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
