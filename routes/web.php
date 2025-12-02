<?php

use Illuminate\Support\Facades\Route;
use App\Models\WorkOrder;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkOrderController;
use App\Models\Plant;

Route::get('/', function () {

    $workOrders = WorkOrder::with('requester')->latest()->paginate(10);

    $stats = [
        'total' => WorkOrder::count(),
        'pending' => WorkOrder::where('work_status', 'pending')->count(),
        'in_progress' => WorkOrder::where('work_status', 'in_progress')->count(),
        'completed' => WorkOrder::where('work_status', 'completed')->count(),
        'cancelled' => WorkOrder::where('work_status', 'cancelled')->count()
    ];
    return view('welcome', compact('workOrders', 'stats'));
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/work-orders', [WorkOrderController::class, 'store'])->name('work-orders.store');
    // Route Update (PUT)
    Route::put('/work-orders/{workOrder}', [WorkOrderController::class, 'update'])->name('work-orders.update');
    Route::get('/work-orders/export', [WorkOrderController::class, 'export'])->name('work-orders.export');
});

require __DIR__ . '/auth.php';
