<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('micro_business_ideas', function (Blueprint $table) {
            $table->index(['is_active', 'name'], 'mbi_active_name_idx');
            $table->index('capital_estimate', 'mbi_capital_estimate_idx');
            $table->index('free_time_min_hours', 'mbi_time_min_idx');
        });

        Schema::table('micro_business_idea_scores', function (Blueprint $table) {
            $table->index('criterion_id', 'mbis_criterion_idx');
        });

        Schema::table('criteria', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'criteria_active_sort_idx');
        });

        Schema::table('business_master_options', function (Blueprint $table) {
            $table->index(['type', 'is_active', 'sort_order'], 'bmo_type_active_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('micro_business_ideas', function (Blueprint $table) {
            $table->dropIndex('mbi_active_name_idx');
            $table->dropIndex('mbi_capital_estimate_idx');
            $table->dropIndex('mbi_time_min_idx');
        });

        Schema::table('micro_business_idea_scores', function (Blueprint $table) {
            $table->dropIndex('mbis_criterion_idx');
        });

        Schema::table('criteria', function (Blueprint $table) {
            $table->dropIndex('criteria_active_sort_idx');
        });

        Schema::table('business_master_options', function (Blueprint $table) {
            $table->dropIndex('bmo_type_active_sort_idx');
        });
    }
};
