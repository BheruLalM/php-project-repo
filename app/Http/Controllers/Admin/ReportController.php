<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CaseReportingService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportingService;

    public function __construct(CaseReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function index()
    {
        $report = $this->reportingService->getMonthlyReport();
        return view('admin.reports.index', compact('report'));
    }

    public function archiveOld()
    {
        $count = $this->reportingService->archiveOldCases();
        return back()->with('success', "{$count} cases older than 10 years have been archived.");
    }
}
