<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        DB::table('users')
            ->whereNull('username')
            ->orderBy('id')
            ->get(['id', 'email'])
            ->each(function (object $user): void {
                $baseUsername = Str::of((string) $user->email)
                    ->before('@')
                    ->replaceMatches('/[^A-Za-z0-9_]/', '_')
                    ->lower()
                    ->trim('_')
                    ->value();

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $baseUsername !== '' ? $baseUsername . '_' . $user->id : 'user_' . $user->id]);
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
