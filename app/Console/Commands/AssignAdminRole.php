<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-admin-role {email? : The email address of the user to assign admin role to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to a user by email (defaults to admin@example.com)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@example.com';
        
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('Admin role not found in the database.');
            return 1;
        }
        
        // Check if the user already has the admin role
        if ($user->roles()->where('name', 'admin')->exists()) {
            $this->info("User {$email} already has the admin role.");
            return 0;
        }
        
        // Assign the admin role
        $user->roles()->attach($adminRole);
        
        $this->info("Admin role successfully assigned to {$email}.");
        return 0;
    }
}
