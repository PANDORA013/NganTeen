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
        // Update existing users with registration_date based on created_at
        DB::table('users')
            ->whereNull('registration_date')
            ->update([
                'registration_date' => DB::raw('created_at'),
                'last_activity' => DB::raw('created_at'),
                'updated_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set registration_date back to null
        DB::table('users')->update(['registration_date' => null]);
    }
};
