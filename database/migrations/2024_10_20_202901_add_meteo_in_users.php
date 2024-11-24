<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('meteo_city')->nullable();
            $table->decimal('meteo_lat',10,8)->nullable();
            $table->decimal('meteo_lon',10,8)->nullable();
        });
    }
};
