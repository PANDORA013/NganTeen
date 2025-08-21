<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UpdatePasswordUpdatedAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing users with realistic password_updated_at times
        $users = User::whereNull('password_updated_at')->get();
        
        foreach ($users as $user) {
            // Set password_updated_at to a random time between creation and now
            $createdAt = $user->created_at;
            $now = Carbon::now();
            
            // Generate a random date between created_at and now
            $randomDays = rand(0, $createdAt->diffInDays($now));
            $passwordUpdatedAt = $createdAt->copy()->addDays($randomDays);
            
            $user->update([
                'password_updated_at' => $passwordUpdatedAt
            ]);
        }
        
        $this->command->info('Updated password_updated_at for ' . $users->count() . ' users.');
    }
}
