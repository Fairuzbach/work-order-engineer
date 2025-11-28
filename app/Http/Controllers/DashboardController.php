<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data work order, urutkan dari yang terbaru
        // 'with' digunakan agar query lebih cepat (Eager Loading) relasi user
        $workOrders = WorkOrder::with(['requester'])
            ->latest()
            ->paginate(10); // Tampilkan 10 per halaman

        return view('dashboard', compact('workOrders'));
    }
}
