<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\OfficerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Officer\OfficerDashboardController;
use App\Http\Controllers\CriminalController;
use App\Http\Controllers\CrimeRecordController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\EvidenceController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Officer management
    Route::resource('officers', OfficerController::class);

    // Criminals
    Route::resource('criminals', CriminalController::class);

    // Crime Records
    Route::resource('cases', CrimeRecordController::class);
    Route::post('cases/{case}/archive', [CrimeRecordController::class, 'archive'])->name('cases.archive');

    // Complaints
    Route::resource('complaints', ComplaintController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::post('/reports/archive-old', [ReportController::class, 'archiveOld'])->name('reports.archive');

    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

    // Evidence
    Route::post('evidence/upload', [EvidenceController::class, 'store'])->name('evidence.store');
    Route::delete('evidence/{evidence}', [EvidenceController::class, 'destroy'])->name('evidence.destroy');
});

// Officer routes (officers + admin both access)
Route::prefix('officer')->name('officer.')->middleware(['auth', 'officer'])->group(function () {
    Route::get('/dashboard', [OfficerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('criminals', CriminalController::class)->except(['destroy']);
    Route::resource('cases', CrimeRecordController::class)->except(['destroy']);
    Route::resource('complaints', ComplaintController::class)->except(['destroy']);
    Route::post('evidence/upload', [EvidenceController::class, 'store'])->name('evidence.store');
});

// Redirect /dashboard to role-specific dashboard if authenticated
Route::get('/dashboard', function() {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('officer.dashboard');
})->middleware('auth')->name('dashboard');

// require __DIR__.'/auth.php'; // We are using a custom AuthController as requested
