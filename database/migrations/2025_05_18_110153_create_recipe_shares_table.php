<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->string('share_token')->unique();
            $table->foreignId('sharer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
};
