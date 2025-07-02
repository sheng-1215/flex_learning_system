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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cu_id')->constrained("cu_activities")->onDelete('cascade');
            $table->string("assignment_name");
            $table->text("description")->nullable();
            $table->dateTime("due_date");
            $table->string("attachment")->nullable(); // Path to the uploaded assignment file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
