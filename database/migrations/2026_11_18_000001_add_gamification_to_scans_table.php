<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scans', function (Blueprint $table) {
            $table->unsignedInteger('xp_earned')->default(0)->after('confidence');
            // sha1 isi file, dipakai untuk mencegah foto yang sama dihitung XP berkali-kali
            $table->string('image_hash', 40)->nullable()->index()->after('xp_earned');
        });
    }

    public function down(): void
    {
        Schema::table('scans', function (Blueprint $table) {
            $table->dropIndex(['image_hash']);
            $table->dropColumn(['xp_earned', 'image_hash']);
        });
    }
};
