<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This is a code-only migration to document that we've updated the ExamController::submit 
     * method to include course information in the question_details JSON. 
     * This enhances the reporting capabilities for subject-based performance analysis.
     */
    public function up(): void
    {
        // No actual database changes needed, only code changes in ExamController
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No database changes to revert
    }
};
