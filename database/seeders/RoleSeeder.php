<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full access'
            ],
            [
                'name' => 'teacher',
                'description' => 'Teacher with access to create content'
            ],
            [
                'name' => 'student',
                'description' => 'Regular student user'
            ],
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']], 
                ['description' => $role['description']]
            );
        }
        
        // Assign admin role to user with ID 1
        $admin = User::find(1);
        if ($admin) {
            $adminRole = Role::where('name', 'admin')->first();
            if (!$admin->roles()->where('name', 'admin')->exists()) {
                $admin->roles()->attach($adminRole);
            }
        }
        
        // Assign student role to all other users
        $studentRole = Role::where('name', 'student')->first();
        User::where('id', '>', 1)->get()->each(function ($user) use ($studentRole) {
            if (!$user->roles()->where('name', 'student')->exists()) {
                $user->roles()->attach($studentRole);
            }
        });
    }
}
