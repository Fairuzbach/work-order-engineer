<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\WorkOrder;
use App\Models\Technician;         // 1. Import Model Teknisi
use App\Models\ProductionStatus;   // 2. Import Model Status Produksi
use App\Models\MaintenanceStatus;  // 3. Import Model Status Maintenance
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- A. AMBIL DATA MASTER ---
        // Data ini diperlukan untuk dropdown di Modal Create & Edit
        $plants = Plant::with('machines')->get();
        $technicians = Technician::all();               // Ambil semua teknisi
        $productionStatuses = ProductionStatus::all();  // Ambil status produksi
        $maintenanceStatuses = MaintenanceStatus::all(); // Ambil status maintenance

        // --- B. QUERY WORK ORDER (PENCARIAN) ---
        // Kita mulai query builder dari sini
        $query = WorkOrder::with(['requester']);

        if ($request->filled('search')) {
            $search = $request->search;

            // Filter data berdasarkan kata kunci
            $query->where(function ($q) use ($search) {
                $q->where('ticket_num', 'like', "%{$search}%")
                    ->orWhere('plant', 'like', "%{$search}%")
                    ->orWhere('machine_name', 'like', "%{$search}%")
                    ->orWhere('damaged_part', 'like', "%{$search}%")
                    ->orWhere('kerusakan', 'like', "%{$search}%");
            });
        }

        // --- C. EKSEKUSI QUERY ---
        // Jalankan query dengan pagination
        // withQueryString() penting agar parameter search tidak hilang saat ganti halaman
        $workOrders = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // --- D. KIRIM SEMUA DATA KE VIEW ---
        // Pastikan 'technicians', 'productionStatuses', dll masuk di compact()
        return view('dashboard', compact(
            'workOrders',
            'plants',
            'technicians',
            'productionStatuses',
            'maintenanceStatuses'
        ));
    }
}
