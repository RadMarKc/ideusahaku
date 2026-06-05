<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_master_options', function (Blueprint $table) {
            if (! Schema::hasColumn('business_master_options', 'value_min')) {
                $table->unsignedBigInteger('value_min')->nullable()->after('score');
            }

            if (! Schema::hasColumn('business_master_options', 'value_max')) {
                $table->unsignedBigInteger('value_max')->nullable()->after('value_min');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE business_master_options MODIFY type ENUM('capital', 'location', 'time') NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('business_master_options', function (Blueprint $table) {
            if (Schema::hasColumn('business_master_options', 'value_min')) {
                $table->dropColumn('value_min');
            }

            if (Schema::hasColumn('business_master_options', 'value_max')) {
                $table->dropColumn('value_max');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE business_master_options MODIFY type ENUM('location', 'time') NOT NULL");
        }
    }
};
