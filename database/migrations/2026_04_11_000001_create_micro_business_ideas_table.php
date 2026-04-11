<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('micro_business_ideas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Modal (IDR)
            $table->unsignedBigInteger('capital_min')->default(0);
            $table->unsignedBigInteger('capital_max')->nullable();

            // Waktu luang (jam per minggu)
            $table->unsignedTinyInteger('free_time_min_hours')->default(0);
            $table->unsignedTinyInteger('free_time_max_hours')->nullable();

            // Lokasi (kategori), disimpan sebagai array kode lokasi
            $table->json('suitable_locations')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('micro_business_ideas');
    }
};

