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
Schema::create('scans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('waste_type_id')->nullable()->constrained()->nullOnDelete();
    $table->string('image_path');
    $table->decimal('confidence', 5, 2)->nullable(); // mis. 94.50
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
