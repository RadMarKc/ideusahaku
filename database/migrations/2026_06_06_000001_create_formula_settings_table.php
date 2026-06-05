<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formula_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('modal_weight', 5, 2)->default(0.45);
            $table->decimal('location_weight', 5, 2)->default(0.30);
            $table->decimal('time_weight', 5, 2)->default(0.25);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formula_settings');
    }
};
