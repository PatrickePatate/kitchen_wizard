<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_validation_code')->nullable()->after('telegram_chat_id');
            $table->boolean('telegram_validated')->default(false)->after('telegram_validation_code');
            $table->dropColumn('telegram_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telegram_validation_code');
            $table->dropColumn('telegram_validated');
            $table->string('telegram_user_id')->nullable()->after('telegram_chat_id');
        });
    }
};
