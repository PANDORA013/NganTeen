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
        Schema::table('global_orders', function (Blueprint $table) {
            // Add warung relationship
            if (!Schema::hasColumn('global_orders', 'warung_id')) {
                $table->foreignId('warung_id')->nullable()->constrained('warungs')->after('buyer_id');
            }
            
            // Add customer info for centralized payment
            if (!Schema::hasColumn('global_orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('warung_id');
            }
            if (!Schema::hasColumn('global_orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('global_orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_phone');
            }
            
            // Add settlement notes
            if (!Schema::hasColumn('global_orders', 'settlement_notes')) {
                $table->text('settlement_notes')->nullable()->after('settled_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_orders', function (Blueprint $table) {
            $table->dropForeign(['warung_id']);
            $table->dropColumn([
                'warung_id',
                'customer_name',
                'customer_phone', 
                'customer_email',
                'settlement_notes'
            ]);
        });
    }
};
