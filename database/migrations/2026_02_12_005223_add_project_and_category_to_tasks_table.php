<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            
            // Only add category if it doesn't exist
            if (!Schema::hasColumn('tasks', 'category')) {
                $table->enum('category', ['frontend', 'backend', 'server'])->after('description');
            }
            
            $table->foreignId('assigned_developer_id')->nullable()->after('category')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['assigned_developer_id']);
            $table->dropColumn(['project_id', 'category', 'assigned_developer_id']);
        });
    }
};
