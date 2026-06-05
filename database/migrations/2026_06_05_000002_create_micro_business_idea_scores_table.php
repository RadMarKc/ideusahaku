<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('micro_business_idea_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('micro_business_idea_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('criterion_id')
                ->constrained('criteria')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->default(0);
            $table->timestamps();

            $table->unique(['micro_business_idea_id', 'criterion_id'], 'idea_criterion_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('micro_business_idea_scores');
    }
};
