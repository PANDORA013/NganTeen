<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UpdateLastLoginAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing users with realistic last_login_at times
        $users = User::whereNull('last_login_at')->get();
        
        foreach ($users as $user) {
            // Set last_login_at to a random time between creation and now
            $createdAt = $user->created_at;
            $now = Carbon::now();
            
            // Generate a random recent login time (within last 30 days)
            $maxDaysBack = min(30, $createdAt->diffInDays($now));
            $randomDays = rand(0, $maxDaysBack);
            $randomHours = rand(0, 23);
            $randomMinutes = rand(0, 59);
            
            $lastLoginAt = $now->copy()->subDays($randomDays)->setTime($randomHours, $randomMinutes);
            
            // Make sure it's not before creation date
            if ($lastLoginAt->lt($createdAt)) {
                $lastLoginAt = $createdAt->copy()->addMinutes(rand(1, 60));
            }
            
            $user->update([
                'last_login_at' => $lastLoginAt
            ]);
        }
        
        $this->command->info('Updated last_login_at for ' . $users->count() . ' users.');
    }
}
