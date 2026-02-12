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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'assigned', 'in-progress', 'review', 'completed'])->default('pending');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('developer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('completion_proof')->nullable();
            $table->timestamps();
            
            $table->index(['developer_id', 'status']);
            $table->index(['customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
