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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('career_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('first_login')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropForeign(['career_id']);
            $table->dropColumn(['university_id', 'career_id', 'first_login']);
        });
    }
};
