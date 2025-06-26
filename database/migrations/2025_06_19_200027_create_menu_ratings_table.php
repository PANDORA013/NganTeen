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
        if (!Schema::hasTable('menu_ratings')) {
            Schema::create('menu_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->tinyInteger('rating')->unsigned()->between(1, 5);
                $table->text('review')->nullable();
                $table->timestamps();
                
                $table->unique(['menu_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_ratings');
    }
};
