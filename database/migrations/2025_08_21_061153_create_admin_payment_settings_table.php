<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method')->default('qris'); // qris, bank_transfer, etc
            $table->string('qris_image')->nullable(); // Path to QRIS image
            $table->string('merchant_name')->default('NganTeen Official');
            $table->string('merchant_id')->nullable();
            
            // Bank transfer settings (backup payment)
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            
            // Commission settings
            $table->decimal('platform_fee_percentage', 5, 2)->default(5.00); // 5% platform fee
            $table->decimal('payment_fee_fixed', 10, 2)->default(2500); // Rp 2,500 payment fee
            
            // Payment gateway settings (future use)
            $table->string('payment_gateway')->nullable(); // midtrans, xendit, etc
            $table->text('gateway_config')->nullable(); // JSON config
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_payment_settings');
    }
};
