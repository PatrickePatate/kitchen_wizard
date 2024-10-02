<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('recipe_daily_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->json('recipes_selection');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_daily_selections');
    }
};
