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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('explanation');
            $table->text('problem_statement')->nullable()->after('question_text');
            $table->boolean('is_problem_solving')->default(false)->after('difficulty_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('video_url');
            $table->dropColumn('problem_statement');
            $table->dropColumn('is_problem_solving');
        });
    }
};
