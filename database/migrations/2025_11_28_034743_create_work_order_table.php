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
        // PERBAIKAN 1: Nama tabel harus jamak (plural) -> 'work_orders'
        // Agar otomatis terbaca oleh Model Laravel tanpa setting tambahan.
        Schema::create('work_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // PERBAIKAN 2: Relasi ke tabel Users
            // Gunakan 'foreignId' jika tabel users Anda menggunakan ID angka biasa (default Laravel).
            // Jika tabel users Anda pakai UUID, ganti jadi $table->foreignUuid(...)
            $table->foreignUuid('requester_id')->constrained('users')->onDelete('cascade');

            // DATA UTAMA (Sesuai Form Dashboard Baru)
            $table->string('ticket_num')->unique();
            $table->date('report_date')->nullable();       // Baru
            $table->time('report_time')->nullable();       // Baru
            $table->string('shift')->nullable();           // Baru
            $table->string('plant')->nullable();           // Baru
            $table->string('machine_name')->nullable();    // Baru
            $table->string('damaged_part')->nullable();    // Baru (Bagian Rusak)
            $table->string('production_status')->nullable(); // Baru

            // DATA DETAIL KERUSAKAN
            $table->string('kerusakan');      // Judul singkat
            $table->text('kerusakan_detail'); // Penjelasan rinci

            // FILE UPLOAD
            $table->string('photo_path')->nullable(); // Baru (Untuk Foto)

            // STATUS & PRIORITAS
            // Menambahkan default value agar tidak error jika kosong
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
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
