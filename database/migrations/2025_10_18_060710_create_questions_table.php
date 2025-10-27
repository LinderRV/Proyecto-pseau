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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('difficulty_level')->default('Intermedio'); // Básico, Intermedio, Avanzado
            $table->text('explanation')->nullable(); // Explanation for the correct answer
            $table->foreignId('university_id')->nullable()->constrained()->onDelete('set null');
            $table->string('question_type')->default('multiple_choice'); // multiple_choice, true_false, etc.
            $table->string('image')->nullable(); // Path to question image if any
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
