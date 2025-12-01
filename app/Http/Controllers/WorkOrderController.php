<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkOrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Data
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        // 2. Handle Upload Foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('work_orders', 'public');
        }

        // 3. Simpan ke Database
        WorkOrder::create([
            // Data Pelapor & Tiket
            'requester_id' => auth()->id(),
            'ticket_num' => 'WO-' . date('Ymd') . '-' . strtoupper(Str::random(4)),

            // Data Waktu & Lokasi
            'report_date' => $request->report_date,
            'report_time' => $request->report_time,
            'shift' => $request->shift,
            'plant' => $request->plant,
            'machine_name' => $request->machine_name,

            // Data Kerusakan
            'damaged_part' => $request->damaged_part,
            'production_status' => $request->production_status,
            'kerusakan' => $request->damaged_part, // Judul disamakan dengan bagian rusak
            'kerusakan_detail' => $request->kerusakan_detail,

            // Data Tambahan
            'priority' => $request->priority ?? 'medium',
            'work_status' => 'pending',
            'photo_path' => $photoPath,
        ]);

        // 4. Redirect kembali ke Dashboard
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat! Teknisi akan segera meninjau.');
    }
}
