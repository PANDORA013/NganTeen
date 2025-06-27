<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'List all users in the database';

    public function handle()
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->info('No users found in the database.');
            return;
        }
        
        $headers = ['ID', 'Name', 'Email', 'Role', 'Created At'];
        $rows = [];
        
        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        $this->table($headers, $rows);
        return 0;
    }
}
