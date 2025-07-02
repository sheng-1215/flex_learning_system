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
        Schema::create('assignment_submits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('attachment')->nullable(); // Path to the submitted assignment file
            $table->text('comments')->nullable(); // Comments from the student or instructor
            $table->enum('status', ['submitted', 'graded', 'revised'])->default('submitted'); // Status of the submission
            $table->timestamp('submitted_at')->nullable(); // Timestamp when the assignment was submitted
            $table->timestamp('graded_at')->nullable(); // Timestamp when the assignment was graded
            $table->integer('grade')->nullable(); // Grade given
            $table->text('feedback')->nullable(); // Feedback from the instructor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submits');
    }
};
