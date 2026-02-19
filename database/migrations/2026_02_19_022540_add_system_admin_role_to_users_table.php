<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add a temporary column to store existing roles
        Schema::table('users', function (Blueprint $table) {
            $table->string('temp_role')->nullable();
        });

        // Copy existing roles to temporary column
        DB::statement('UPDATE users SET temp_role = role');

        Schema::table('users', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add the new enum column with system_admin included
            $table->enum('role', ['customer', 'frontend_developer', 'backend_developer', 'server_administrator', 'system_admin'])->after('email')->default('customer');
        });

        // Restore the original roles from temporary column
        DB::statement('UPDATE users SET role = temp_role WHERE temp_role IS NOT NULL');

        // Drop the temporary column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('temp_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the current enum column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Restore the original enum column without system_admin
            $table->enum('role', ['customer', 'frontend_developer', 'backend_developer', 'server_administrator'])->after('email')->default('customer');
        });
    }
};