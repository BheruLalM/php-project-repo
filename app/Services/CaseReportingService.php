<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\CrimeRecord;
use App\Models\Complaint;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CaseReportingService
{
    /**
     * Archive all closed cases older than 10 years.
     *
     * @return int Number of cases archived.
     */
    public function archiveOldCases(): int
    {
        $cutoff = now()->subYears(10);

        $cases = CrimeRecord::where('status', 'closed')
            ->where('date_of_occurrence', '<', $cutoff)
            ->whereNull('archived_at')
            ->get();

        $count = 0;

        foreach ($cases as $case) {
            $case->update([
                'status'      => 'archived',
                'archived_at' => now(),
            ]);

            // Log the archiving action
            AuditLog::create([
                'user_id'      => Auth::id(),
                'action'       => 'archived',
                'subject_type' => 'CrimeRecord',
                'subject_id'   => $case->id,
                'description'  => "Case {$case->case_number} auto-archived (10+ years old, closed status).",
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Get summary statistics for the current month.
     *
     * @return array<string, int|array>
     */
    public function getMonthlyReport(): array
    {
        $month = now()->month;
        $year  = now()->year;

        $baseQuery = fn () => CrimeRecord::whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $byCrimeType = CrimeRecord::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('crime_type, COUNT(*) as total')
            ->groupBy('crime_type')
            ->pluck('total', 'crime_type')
            ->toArray();

        return [
            'total_open'                => $baseQuery()->where('status', 'open')->count(),
            'total_closed'              => $baseQuery()->where('status', 'closed')->count(),
            'total_under_investigation' => $baseQuery()->where('status', 'under_investigation')->count(),
            'total_archived'            => $baseQuery()->where('status', 'archived')->count(),
            'new_complaints'            => Complaint::whereMonth('created_at', $month)
                                            ->whereYear('created_at', $year)
                                            ->count(),
            'by_crime_type'             => $byCrimeType,
        ];
    }

    /**
     * Get all cases assigned to a specific officer (with criminal eager loaded).
     *
     * @param  int  $officerId
     * @return Collection
     */
    public function getCasesByOfficer(int $officerId): Collection
    {
        return CrimeRecord::with(['criminal', 'evidences'])
            ->where('assigned_officer_id', $officerId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get all open cases older than the given number of days.
     * Useful for dashboard alerts on stale cases.
     *
     * @param  int  $days
     * @return Collection
     */
    public function getOpenCasesOlderThan(int $days): Collection
    {
        return CrimeRecord::open()
            ->where('created_at', '<', now()->subDays($days))
            ->with(['criminal', 'officer'])
            ->orderBy('created_at')
            ->get();
    }
}
