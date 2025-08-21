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
            // Add centralized payment fields
            if (!Schema::hasColumn('global_orders', 'platform_fee')) {
                $table->decimal('platform_fee', 10, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('global_orders', 'payment_fee')) {
                $table->decimal('payment_fee', 10, 2)->default(0)->after('platform_fee');
            }
            if (!Schema::hasColumn('global_orders', 'gross_amount')) {
                $table->decimal('gross_amount', 10, 2)->default(0)->after('payment_fee');
            }
            if (!Schema::hasColumn('global_orders', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->default(0)->after('gross_amount');
            }
            
            // Payment method info - skip if exists
            if (!Schema::hasColumn('global_orders', 'admin_qris_used')) {
                $table->string('admin_qris_used')->nullable()->after('payment_method');
            }
            
            // Settlement info
            if (!Schema::hasColumn('global_orders', 'is_settled')) {
                $table->boolean('is_settled')->default(false)->after('admin_qris_used');
            }
            if (!Schema::hasColumn('global_orders', 'settled_at')) {
                $table->timestamp('settled_at')->nullable()->after('is_settled');
            }
            if (!Schema::hasColumn('global_orders', 'settled_by')) {
                $table->foreignId('settled_by')->nullable()->constrained('users')->after('settled_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_orders', function (Blueprint $table) {
            $table->dropColumn([
                'platform_fee',
                'payment_fee', 
                'gross_amount',
                'net_amount',
                'payment_method',
                'admin_qris_used',
                'is_settled',
                'settled_at',
                'settled_by'
            ]);
        });
    }
};
