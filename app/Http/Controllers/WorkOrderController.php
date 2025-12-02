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

        $dateCode = date('Ymd');
        $prefix = 'engIO-' . $dateCode . '-';

        $lastWorkOrder = WorkOrder::where('ticket_num', 'like', $prefix . '%')->orderBy('id', 'desc')->first();

        if ($lastWorkOrder) {
            $lastNumber = (int) substr($lastWorkOrder->ticket_num, -2);
            $newSequence = $lastNumber + 1;
        } else {
            $newSequence = 0;
        }
        $ticketNum = $prefix . sprintf('%03d', $newSequence);

        WorkOrder::create([
            'requester_id' => auth()->id(),
            'ticket_num' => $ticketNum,
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
    public function export(Request $request)
    {
        // 1. Validasi Input Tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // 2. Ambil Data dari Database
        $data = \App\Models\WorkOrder::with('requester')->whereBetween('report_date', [$startDate, $endDate])
            ->orderBy('report_date', 'asc')
            ->orderBy('report_time', 'asc')
            ->get();

        // 3. Nama File
        $fileName = 'Laporan_WO_' . $startDate . '_sd_' . $endDate . '.csv';

        // 4. Header CSV (Sesuaikan dengan kolom yang Anda inginkan)
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // 5. Kolom Judul di dalam file CSV
        $columns = [
            'No Tiket',
            'Tanggal Lapor',
            'Jam',
            'ID Pelapor',
            'Nama Pelapor',
            'Shift',
            'Plant',
            'Mesin',
            'Request', //
            'Prioritas',
            'Status',
            'Engineer',
            'Uraian Improvement',
            'Sparepart',
            'Tanggal Selesai'
        ];

        // 6. Callback untuk stream download
        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');

            // Tulis Judul Kolom
            fputcsv($file, $columns);

            // Tulis Baris Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->ticket_num, //nomor tiket
                    $row->report_date,
                    $row->report_time,
                    $row->requester_id,
                    $row->requester->name ?? 'NO NAME, CEK ID PELAPOR', // Asumsi ada relasi atau kolom ini
                    $row->shift,
                    $row->plant,
                    $row->machine_name,
                    $row->damaged_part, //REQUEST
                    $row->priority,
                    $row->work_status,
                    $row->technician, //ENGINEER
                    $row->repair_solution, //URAIAN IMPROVEMENT
                    $row->sparepart,
                    $row->finished_date
                ]);
            }
            fclose($file);
        };

        // 7. Return Stream Download
        return response()->stream($callback, 200, $headers);
    }
}
