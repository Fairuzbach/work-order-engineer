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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();

            // Relasi User
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');

            // Data Awal (Saat Register)
            $table->string('ticket_num')->unique();
            $table->date('report_date')->nullable();
            $table->time('report_time')->nullable();
            $table->string('shift')->nullable();
            $table->string('plant')->nullable();
            $table->string('machine_name')->nullable();
            $table->string('damaged_part')->nullable();
            $table->string('production_status')->nullable();
            $table->string('kerusakan');
            $table->text('kerusakan_detail');
            $table->string('photo_path')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            // Data Update (Saat Edit Admin) - PASTIKAN INI ADA
            $table->date('finished_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('technician')->nullable();
            $table->string('maintenance_note')->nullable();
            $table->text('repair_solution')->nullable();
            $table->text('sparepart')->nullable();

            // Status
            $table->enum('work_status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
