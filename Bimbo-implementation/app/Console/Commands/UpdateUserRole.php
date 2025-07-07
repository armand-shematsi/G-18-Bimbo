<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUserRole extends Command
{
    protected $signature = 'user:update-role {user_id} {role}';
    protected $description = 'Update user role';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $role = $this->argument('role');

        // Show current users
        $this->info('Current users and their roles:');
        $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        foreach ($users as $user) {
            $this->line("ID: {$user->id} - Name: {$user->name} - Role: {$user->role}");
        }

        // Update the specific user
        $this->info("\nUpdating user with ID {$userId} to have '{$role}' role...");
        $updated = DB::table('users')->where('id', $userId)->update(['role' => $role]);

        if ($updated) {
            $this->info('Updated successfully!');
        } else {
            $this->error('Failed to update user. User might not exist.');
        }

        // Show updated users
        $this->info("\nUpdated users and their roles:");
        $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        foreach ($users as $user) {
            $this->line("ID: {$user->id} - Name: {$user->name} - Role: {$user->role}");
        }
    }
} 