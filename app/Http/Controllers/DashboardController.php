<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\WorkOrder;
use App\Models\Technician;
use App\Models\ProductionStatus;
use App\Models\MaintenanceStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- A. AMBIL DATA MASTER ---

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

        if ($request->filled('work_status')) {
            $query->where('work_status', $request->work_status);
        }

        // --- C. EKSEKUSI QUERY ---

        $workOrders = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // --- D. KIRIM SEMUA DATA KE VIEW ---

        return view('dashboard', compact(
            'workOrders',
            'plants',
            'technicians',
            'productionStatuses',
            'maintenanceStatuses'
        ));
    }
}
