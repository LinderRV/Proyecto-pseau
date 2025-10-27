<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add admin role if it doesn't exist
        $roleExists = DB::table('roles')->where('name', 'admin')->exists();
        
        if (!$roleExists) {
            DB::table('roles')->insert([
                'name' => 'admin',
                'description' => 'Administrator with full access to all features',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Assign admin role to user with ID 1 if exists
        $user = DB::table('users')->where('id', 1)->first();
        
        if ($user) {
            $adminRole = DB::table('roles')->where('name', 'admin')->first();
            
            // Check if relationship already exists
            $relationshipExists = DB::table('role_user')
                ->where('user_id', 1)
                ->where('role_id', $adminRole->id)
                ->exists();
                
            if (!$relationshipExists && $adminRole) {
                DB::table('role_user')->insert([
                    'role_id' => $adminRole->id,
                    'user_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove admin role assignment from user with ID 1
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        
        if ($adminRole) {
            DB::table('role_user')
                ->where('user_id', 1)
                ->where('role_id', $adminRole->id)
                ->delete();
        }
    }
};
