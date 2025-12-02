<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\Technician;
use App\Models\Machine;
use App\Models\MaintenanceStatus;
use App\Models\ProductionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        //
        Technician::truncate();
        Schema::enableForeignKeyConstraints();

        $newTechnician = [
            ['name' => 'Abdul Halid.A'],
            ['name' => 'Adi Suandri'],
            ['name' => 'Aditya Ramadhan'],
            ['name' => 'Andy Apriadi'],
            ['name' => 'Christian Bayu'],
            ['name' => 'Daffa Abdul.A'],
            ['name' => 'Danu Mamlukat'],
            ['name' => 'Dwi Hastuti'],
            ['name' => 'Edy Murtopo'],
            ['name' => 'Hasiri'],
            ['name' => 'Joko Purnomo'],
            ['name' => 'Khoirul Munasyikin'],
            ['name' => 'Maidafitri Dewi.P'],
            ['name' => 'Mulyana'],
            ['name' => 'Muhammad Andrian'],
            ['name' => 'Rahmat Tammu'],
            ['name' => 'Sudranto Purba'],
            ['name' => 'Teguh Mulyawan'],
            ['name' => 'Tri Wahyu.H'],
            ['name' => 'Yosep Fajar.B.K'],
        ];
        foreach ($newTechnician as $tech) {
            Technician::create($tech);
        }

        $this->command->info('Data technician berhasil diganti');

        $machinesToDelete = [
            'Heli 2,5 Ton',
            'Heli 5 Ton',
            'Heli 1,8 Ton',
            'Nissan 3,5 Ton',
            'Heli 10 Ton',
            'Heli 3,5 Ton',
            'Nissan 2,5 Ton',
            'Heli 7 Ton',
            'Heli 10 Ton'
        ];

        Machine::whereIn('name', $machinesToDelete)->delete();

        $this->command->info('Sebagian data telah di hapus');

        ProductionStatus::truncate();
        $newProd = [
            ['code' => 'M', 'name' => 'Machines'],
            ['code' => 'Mt', 'name' => 'Materials'],
            ['code' => 'T', 'name' => 'Tools'],
        ];
        foreach ($newProd as $prod) {
            ProductionStatus::create($prod);
        }

        MaintenanceStatus::truncate();
        $newMaintenance = [
            ['code' => 'M', 'name' => 'Machines'],
            ['code' => 'Mt', 'name' => 'Materials'],
            ['code' => 'T', 'name' => 'Tools'],
        ];
        foreach ($newMaintenance as $newMt) {
            MaintenanceStatus::create($newMt);
        }
    }
}
