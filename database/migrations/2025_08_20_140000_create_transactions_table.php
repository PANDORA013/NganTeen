<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['payment', 'refund'])->default('payment');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_gateway')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
