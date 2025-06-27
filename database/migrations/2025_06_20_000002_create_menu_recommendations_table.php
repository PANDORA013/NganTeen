<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_recommendations', function (Blueprint $table) {
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('recommended_id')->constrained('menus')->onDelete('cascade');
            $table->integer('weight')->default(1);
            
            $table->primary(['menu_id', 'recommended_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_recommendations');
    }
};
