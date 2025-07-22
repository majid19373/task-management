<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\{TaskPriorityEnum, TaskStatusEnum};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade');
            $table->string('title', 100);
            $table->string('description', 500)->nullable();
            $table->enum('status', TaskStatusEnum::toArray())->default(TaskStatusEnum::NOT_STARTED->value);
            $table->enum('priority', TaskPriorityEnum::toArray())->default(TaskPriorityEnum::MEDIUM->value);
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
