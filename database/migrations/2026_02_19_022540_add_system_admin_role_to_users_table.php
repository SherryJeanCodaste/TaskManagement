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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add the new enum column with system_admin included
            $table->enum('role', ['customer', 'frontend_developer', 'backend_developer', 'server_administrator', 'system_admin'])->after('email')->default('customer');
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