<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call our custom seeders
        $this->call([
            UniversitySeeder::class,
            CareerSeeder::class,
            CourseSeeder::class,
            QuestionSeeder::class,
            AdditionalQuestionsSeeder::class,
        ]);

        // Create the admin user first to ensure it gets ID 1
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'first_login' => false
            ]
        );
        
        // Then create a regular test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Manually assign the admin role to prevent issues with seeders
        $this->call(RoleSeeder::class);
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->roles()->where('name', 'admin')->exists()) {
            $admin->roles()->attach($adminRole);
            $this->command->info('Admin role assigned to admin@example.com');
        }
    }
}
