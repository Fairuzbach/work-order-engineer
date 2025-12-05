<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkOrderController extends Controller
{
    // --- FUNGSI STORE (DIPERBAIKI) ---
    public function store(Request $request)
    {
        $request->validate([
            'report_date' => 'required|date',
            'report_time' => 'required',
            'shift' => 'required|string',
            'plant' => 'required|string',
            'machine_name' => 'required|string',
            'damaged_part' => 'required|string',

            // PERBAIKAN: Ubah maintenance_statuses menjadi improvement_status sesuai nama di View
            'improvement_status' => 'required|string',

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

            // PERBAIKAN: Mapping ke database (pastikan nama kolom di DB sesuai, misal: maintenance_statuses atau improvement_status)
            // Jika kolom di DB masih 'maintenance_statuses', gunakan kode ini:
            'maintenance_statuses' => $request->improvement_status,

            'kerusakan' => $request->damaged_part,
            'kerusakan_detail' => $request->kerusakan_detail,
            'priority' => $request->priority ?? 'medium',
            'work_status' => 'pending',
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat!');
    }

    // --- FUNGSI UPDATE (Biarkan Tetap Sama) ---
    public function update(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'work_status' => 'required|in:pending,in_progress,completed,cancelled',
            'finished_date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'technician' => 'nullable|string|max:255',
            'maintenance_note' => 'nullable|string',
            'repair_solution' => 'required|string',
            'sparepart' => 'nullable|string',
        ]);

        $workOrder->update([
            'work_status' => $request->work_status,
            'finished_date' => $request->finished_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'technician' => $request->technician,
            'maintenance_note' => $request->maintenance_note,
            'repair_solution' => $request->repair_solution,
            'sparepart' => $request->sparepart,
        ]);

        return redirect()->route('dashboard')->with('success', 'Status laporan #' . $workOrder->ticket_num . ' berhasil diperbarui!');
    }

    // --- FUNGSI EXPORT (Biarkan Tetap Sama) ---
    public function export(Request $request)
    {

        if ($request->filled('ticket_ids')) {

            $ids = explode(',', $request->ticket_ids);


            $data = \App\Models\WorkOrder::with('requester')
                ->whereIn('id', $ids)
                ->orderBy('report_date', 'asc')
                ->get();

            $fileName = 'Laporan_engIO_Selected_' . date('Ymd_His') . '.csv';
        } else {
            // Validasi HANYA berjalan jika masuk blok else ini

            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $data = \App\Models\WorkOrder::with('requester')
                ->whereBetween('report_date', [$startDate, $endDate])
                ->orderBy('report_date', 'asc')
                ->orderBy('report_time', 'asc')
                ->get();

            $fileName = 'Laporan_engIO_' . $startDate . '_sd_' . $endDate . '.csv';
        }

        // --- CSV WRITER ---
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = [
            'No Tiket',
            'Tanggal Lapor',
            'Jam',
            'ID Pelapor',
            'Nama Pelapor',
            'Shift',
            'Plant',
            'Mesin',
            'Request',
            'Prioritas',
            'Status',
            'Engineer',
            'Uraian Improvement',
            'Sparepart',
            'Tanggal Selesai'
        ];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');

            // Tulis Header
            fputcsv($file, $columns);

            // Tulis Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->ticket_num,
                    \Carbon\Carbon::parse($row->report_date)->format('Y-m-d'),
                    \Carbon\Carbon::parse($row->report_time)->format('H:i'),
                    $row->requester_id,
                    $row->requester->name ?? 'NO NAME, CEK ID PELAPOR',
                    $row->shift,
                    $row->plant,
                    $row->machine_name,
                    $row->damaged_part,
                    $row->priority,
                    $row->work_status,
                    $row->technician,
                    $row->repair_solution,
                    $row->sparepart,

                    $row->finished_date ? \Carbon\Carbon::parse($row->finished_date)->format('Y-m-d') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
