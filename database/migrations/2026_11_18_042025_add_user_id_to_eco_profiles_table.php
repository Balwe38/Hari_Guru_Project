<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('eco_profiles', function (Blueprint $table) {
        if (!Schema::hasColumn('eco_profiles', 'user_id')) {
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
        }
    });
}

public function down(): void
{
    Schema::table('eco_profiles', function (Blueprint $table) {
        if (Schema::hasColumn('eco_profiles', 'user_id')) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        }
    });
}
};
