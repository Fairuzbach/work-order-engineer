<?php

use Illuminate\Support\Facades\Route;
use App\Models\WorkOrder;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkOrderController;
use App\Models\Plant;

Route::get('/', function () {
    $liveReports = WorkOrder::with('requester')->where('created_at', '>=', now()->subDay())->latest()->get();

    $stats = [
        'total' => WorkOrder::count(),
        'pending' => WorkOrder::where('work_status', 'pending')->count(),
        'in_progress' => WorkOrder::where('work_status', 'in_progress')->count(),
        'completed' => WorkOrder::where('work_status', 'completed')->count(),
        'cancelled' => WorkOrder::where('work_status', 'cancelled')->count()
    ];
    $workOrders = WorkOrder::with('requester')->latest()->paginate(5);
    return view('welcome', compact('liveReports', 'workOrders', 'stats'));
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/work-orders', [WorkOrderController::class, 'store'])->name('work-orders.store');
    Route::get('/work-orders', function () {
        return redirect()->route('dashboard');
    });
    // Route Update (PUT)
    Route::get('/work-orders/export', [WorkOrderController::class, 'export'])->name('work-orders.export')->middleware(['throttle:3,1']);
    Route::put('/work-orders/{workOrder}', [WorkOrderController::class, 'update'])->name('work-orders.update')->whereNumber('workOrder ');
});

require __DIR__ . '/auth.php';
