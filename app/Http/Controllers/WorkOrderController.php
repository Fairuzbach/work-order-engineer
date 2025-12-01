<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkOrderController extends Controller
{
    // --- FUNGSI SIMPAN BARU (STORE) ---
    public function store(Request $request)
    {
        $request->validate([
            'report_date' => 'required|date',
            'report_time' => 'required',
            'shift' => 'required|string',
            'plant' => 'required|string',
            'machine_name' => 'required|string',
            'damaged_part' => 'required|string',
            'production_status' => 'required|string',
            'kerusakan_detail' => 'required|string',
            'priority' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('work_orders', 'public');
        }

        WorkOrder::create([
            'requester_id' => auth()->id(),
            'ticket_num' => 'WO-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'report_date' => $request->report_date,
            'report_time' => $request->report_time,
            'shift' => $request->shift,
            'plant' => $request->plant,
            'machine_name' => $request->machine_name,
            'damaged_part' => $request->damaged_part,
            'production_status' => $request->production_status,
            'kerusakan' => $request->damaged_part,
            'kerusakan_detail' => $request->kerusakan_detail,
            'priority' => $request->priority ?? 'medium',
            'work_status' => 'pending',
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat!');
    }

    // --- FUNGSI UPDATE STATUS (EDIT) ---
    // Tambahkan fungsi ini di bawah fungsi store
    public function update(Request $request, WorkOrder $workOrder)
    {
        // 1. Validasi Input Edit
        $request->validate([
            'work_status' => 'required|in:pending,in_progress,completed,cancelled',
            'finished_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'technician' => 'nullable|string|max:255',
            'maintenance_note' => 'nullable|string',
            'repair_solution' => 'nullable|string',
            'sparepart' => 'nullable|string',
        ]);

        // 2. Update Data ke Database
        $workOrder->update([
            'work_status' => $request->work_status,
            'finished_date' => $request->finished_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'technician' => $request->technician, // Nama Teknisi
            'maintenance_note' => $request->maintenance_note,
            'repair_solution' => $request->repair_solution, // Uraian Perbaikan
            'sparepart' => $request->sparepart,
        ]);

        // 3. Kembali ke Dashboard
        return redirect()->route('dashboard')->with('success', 'Status laporan #' . $workOrder->ticket_num . ' berhasil diperbarui!');
    }
}
