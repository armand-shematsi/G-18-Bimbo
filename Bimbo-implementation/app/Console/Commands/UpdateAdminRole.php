<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateAdminRole extends Command
{
    protected $signature = 'admin:update-role';
    protected $description = 'Update the admin user role';

    public function handle()
    {
        $admin = User::where('email', 'admin@bimbo.com')->first();
        
        if ($admin) {
            $admin->role = 'admin';
            $admin->save();
            $this->info('Admin role updated successfully.');
        } else {
            $this->error('Admin user not found.');
        }
    }
} 