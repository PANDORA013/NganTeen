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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type'); // 'order', 'registration', 'menu_created', etc.
            $table->text('description');
            $table->json('properties')->nullable(); // Additional data
            $table->string('subject_type')->nullable(); // Model class name
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
