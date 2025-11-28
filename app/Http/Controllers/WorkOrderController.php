<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. data validation
        $request->validate([
            'kerusakan' => 'required|string|max:255',
            'kerusakan_detail' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        // 2. simpan ke database
        WorkOrder::create([
            'kerusakan' => $request->kerusakan,
            'kerusakan_detail' => $request->kerusakan_detail,
            'priority' => $request->priority,

            // Data Otomatis
            'work_status' => 'pending',
            'requester_id' => auth()->id(),
        ]);


        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat! Teknisi akan segera meninjau.');
    }
}
