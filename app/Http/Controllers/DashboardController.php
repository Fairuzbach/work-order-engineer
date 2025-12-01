<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Data Plant (Untuk dropdown di modal create)
        $plants = Plant::with('machines')->get();

        // 2. Query Dasar (Start Query)
        $query = WorkOrder::with(['requester']);

        // 3. Logika Pencarian (Opsional)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ticket_num', 'like', "%{$search}%")
                    ->orWhere('plant', 'like', "%{$search}%")
                    ->orWhere('machine_name', 'like', "%{$search}%")
                    ->orWhere('damaged_part', 'like', "%{$search}%")
                    ->orWhere('kerusakan', 'like', "%{$search}%");
            });
        }

        // 4. Eksekusi Query (Pagination)
        // Jika tidak di-search, ini akan mengambil semua data (latest)
        $workOrders = $query->latest()
            ->paginate(10)
            ->withQueryString(); // Agar search tidak hilang saat pindah halaman

        // 5. Kirim data ke View
        return view('dashboard', compact('workOrders', 'plants'));
    }
}
