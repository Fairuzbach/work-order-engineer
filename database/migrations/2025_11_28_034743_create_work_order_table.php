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
        Schema::create('work_order', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('requester_id')->constrained('users')->ondelete('cascade');
            $table->string('ticket_num')->unique();
            $table->string('kerusakan');
            $table->text('kerusakan_detail');
            $table->enum('priority', ['low', 'medium', 'high', 'critical']);
            $table->enum('work_status', ['pending', 'in_progress', 'completed', 'cancelled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order');
    }
};
