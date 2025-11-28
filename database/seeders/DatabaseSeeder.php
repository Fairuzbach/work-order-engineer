<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User: REQUESTER (Pelapor)
        // Login pakai email ini nanti
        $superAdmin = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('adminpass123')
        ]);
        $requester = User::create([
            'name' => 'Fairuz Requester',
            'username' => 'fairuz_user', // Tambahan karena tabelmu ada username
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => Hash::make('password'), // Passwordnya: password
        ]);

        // 2. Buat User: TECHNICIAN (Teknisi)
        $technician = User::create([
            'name' => 'Budi Teknisi',
            'username' => 'budi_tech',
            'email' => 'tech@example.com',
            'password' => Hash::make('password'),
        ]);

        // 3. Buat 1 Contoh Tiket Work Order
        WorkOrder::create([
            'ticket_num' => 'WO-2025-001',
            'kerusakan' => 'AC Ruang Server Panas',
            'kerusakan_detail' => 'Suhu ruangan mencapai 30 derajat, AC sepertinya mati total.',
            'priority' => 'high',
            'work_status' => 'pending', // Status awal
            'requester_id' => $requester->id, // Relasi ke Fairuz
            // Teknisi dikosongkan dulu (nullable) karena belum diambil
        ]);

        // 4. Buat 1 Contoh Tiket yang SEDANG DIKERJAKAN
        WorkOrder::create([
            'ticket_num' => 'WO-2025-002',
            'kerusakan' => 'Lampu Gudang Kedap-kedip',
            'kerusakan_detail' => 'Perlu penggantian bohlam segera.',
            'priority' => 'medium',
            'work_status' => 'in_progress',
            'requester_id' => $requester->id
        ]);
    }
}
