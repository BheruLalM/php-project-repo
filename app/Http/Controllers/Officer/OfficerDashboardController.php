<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\CrimeRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $myCases = CrimeRecord::where('assigned_officer_id', $user->id)
            ->with('criminal')
            ->latest()
            ->get();
            
        $myComplaints = Complaint::where('assigned_officer_id', $user->id)
            ->latest()
            ->get();
            
        // Individual stats from assigned cases/complaints
        $stats = [
            'open' => $myCases->where('status', 'open')->count(),
            'under_investigation' => $myCases->where('status', 'under_investigation')->count(),
            'closed' => $myCases->where('status', 'closed')->count(),
            'total_complaints' => $myComplaints->count(),
        ];

        return view('officer.dashboard', compact('myCases', 'myComplaints', 'stats'));
    }
}
