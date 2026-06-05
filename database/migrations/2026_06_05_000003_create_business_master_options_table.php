<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_master_options', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['capital', 'location', 'time']);
            $table->string('code');
            $table->string('label');
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedBigInteger('value_min')->nullable();
            $table->unsignedBigInteger('value_max')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'code']);
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_master_options');
    }
};
