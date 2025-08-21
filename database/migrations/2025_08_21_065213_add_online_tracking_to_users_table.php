<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_online')) {
                $table->boolean('is_online')->default(false)->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->after('is_online');
            }
            if (!Schema::hasColumn('users', 'registration_date')) {
                $table->timestamp('registration_date')->nullable()->after('last_activity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'last_activity', 'registration_date']);
        });
    }
};
