<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\CrimeRecord;
use App\Services\CaseReportingService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $reportingService;

    public function __construct(CaseReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function index()
    {
        $stats = $this->reportingService->getMonthlyReport();
        
        $recentCases = CrimeRecord::with(['criminal', 'officer'])
            ->latest()
            ->take(5)
            ->get();
            
        $openOldCases = $this->reportingService->getOpenCasesOlderThan(30);
        
        $recentComplaints = Complaint::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCases', 'openOldCases', 'recentComplaints'));
    }
}
