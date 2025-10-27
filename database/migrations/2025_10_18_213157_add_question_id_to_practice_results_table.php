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
        Schema::table('practice_results', function (Blueprint $table) {
            $table->foreignId('question_id')->nullable()->after('course_id')->constrained()->onDelete('set null');
            $table->boolean('is_correct')->default(false)->after('time_taken');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practice_results', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropColumn(['question_id', 'is_correct']);
        });
    }
};
