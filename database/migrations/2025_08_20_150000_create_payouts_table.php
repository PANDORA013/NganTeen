<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warung_id')->constrained()->onDelete('cascade');
            $table->string('payout_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->enum('method', ['manual', 'auto'])->default('manual');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payouts');
    }
};
