<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cu_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cu_activities');
    }
}; 