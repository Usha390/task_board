<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->foreignId('assignee_id')->constrained('users')->restrictOnDelete();
        $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
        $table->string('priority', 10)->default('medium');
        $table->date('deadline');
        $table->string('status', 20)->default('todo');

        // Submission (employee jokhon kaj jama dey)
        $table->text('submission_note')->nullable();
        $table->string('submission_file')->nullable();
        $table->text('submission_link')->nullable();
        $table->timestamp('submitted_at')->nullable();
        $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

        // Review (Head jokhon approve/return kore)
        $table->text('feedback')->nullable();
        $table->timestamp('reviewed_at')->nullable();
        $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

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
