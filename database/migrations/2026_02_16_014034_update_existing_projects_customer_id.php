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
        // Update existing projects to assign them to the customer who created the first task
        $projects = DB::table('projects')->whereNull('customer_id')->get();
        
        foreach ($projects as $project) {
            $firstTask = DB::table('tasks')
                ->where('project_id', $project->id)
                ->orderBy('created_at', 'asc')
                ->first();
            
            if ($firstTask) {
                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['customer_id' => $firstTask->customer_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data migration
    }
};
