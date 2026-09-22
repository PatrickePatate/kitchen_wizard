<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('url');
            $table->json('pictures')->nullable();
            $table->string('total_time')->nullable();
            $table->json('times')->nullable();
            $table->string('difficulty')->nullable();
            $table->string('price')->nullable();
            $table->string('meal_type')->nullable();
            $table->json('ingredients');
            $table->json('utensils')->nullable();
            $table->json('steps')->nullable();
            $table->string('author')->nullable();
            $table->text('author_note')->nullable();
            $table->timestamp('scrapped_at')->useCurrent();
            $table->integer('people')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
