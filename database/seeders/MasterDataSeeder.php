<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Technician;
use App\Models\ProductionStatus;
use App\Models\MaintenanceStatus;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DATA TEKNISI
        $technicians = [
            'Suyanto',
            'Samijan',
            'Faisal.K',
            'Sugeng',
            'Arif Aryanto',
            'Ruswidi Joko',
            'Hani Adnani',
            'Teddy Jhonny WN',
            'Aggris Yayit.T',
            'M.Asad Ramadhan',
            'Andhi Kurniasyah',
            'Wahyudin',
            'Tarmono',
            'Suar Sapto',
            'Ali Mustofa',
            'Endang Mulyadi',
            'Ari Muhodari',
            'Turiman',
            'Budiyanto',
            'Ryan Indriyana Rahardi',
            'Dedi Haryawan',
            'Ujang Sudrajat',
            'Doni Ramadoni',
            'Rahmat Hidayat',
            'Firman Hidayat',
            'M.Aziz Toyyibin',
            'Bustami',
            'Rohman',
            'Yulimansyah',
            'Sujarwo',
            'Suyatno',
            'Kasimin',
            'Bakri Sudarmono',
            'M.Basori',
            'Agung Suseno',
            'Widodo',
            'Yakub',
            'Sukino',
            'Awaluddin.F',
            'Suparta'
        ];

        foreach ($technicians as $tech) {
            Technician::create(['name' => $tech]);
        }

        // 2. DATA KETERANGAN PRODUKSI
        $prodStatuses = [
            ['code' => 'M', 'name' => 'Mekanik'],
            ['code' => 'E', 'name' => 'Electricity'],
            ['code' => 'U', 'name' => 'Utility'],
            ['code' => 'C', 'name' => 'Calibraty'],
            ['code' => 'B', 'name' => 'Battery'],
            ['code' => 'BBS', 'name' => 'Bahan Bakar Solar'],
        ];

        foreach ($prodStatuses as $status) {
            ProductionStatus::create($status);
        }

        // 3. DATA KETERANGAN MAINTENANCE
        $maintStatuses = ['TM', 'TE', 'TU', 'LM', 'LE', 'LU'];

        foreach ($maintStatuses as $status) {
            MaintenanceStatus::create([
                'code' => $status,
                'name' => $status // Nama disamakan dengan code karena tidak ada deskripsi panjang
            ]);
        }
    }
}
