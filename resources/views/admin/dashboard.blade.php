@extends('layouts.app')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1>Administrator Dashboard</h1>
    <p class="text-muted">Overview of Law Enforcement Operations</p>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div style="font-size: 0.875rem; opacity: 0.8;">Open Cases</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['total_open'] }}</div>
    </div>
    <div class="stat-card yellow">
        <div style="font-size: 0.875rem; opacity: 0.8;">Investigating</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['total_under_investigation'] }}</div>
    </div>
    <div class="stat-card green">
        <div style="font-size: 0.875rem; opacity: 0.8;">Closed Cases</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['total_closed'] }}</div>
    </div>
    <div class="stat-card red">
        <div style="font-size: 0.875rem; opacity: 0.8;">New Complaints</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['new_complaints'] }}</div>
    </div>
</div>

@if($openOldCases->isNotEmpty())
    <div class="card" style="border-left: 5px solid var(--warning); background: #fffbeb;">
        <h3 style="margin-top: 0; color: #92400e;"><i class="fa-solid fa-triangle-exclamation"></i> Stale Cases Alert</h3>
        <p>The following cases have been open for more than 30 days:</p>
        <ul style="margin-bottom: 0;">
            @foreach($openOldCases as $stale)
                <li>
                    <a href="{{ route('admin.cases.show', $stale->id) }}">
                        <strong>{{ $stale->case_number }}</strong>
                    </a> - Assigned to {{ $stale->officer->name ?? 'Unassigned' }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Cases -->
    <div class="card">
        <h3>Recent Case Records</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Case #</th>
                    <th>Type</th>
                    <th>Criminal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentCases as $case)
                <tr>
                    <td><a href="{{ route('admin.cases.show', $case->id) }}"><strong>{{ $case->case_number }}</strong></a></td>
                    <td>{{ $case->crime_type }}</td>
                    <td>{{ $case->criminal->full_name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '-', $case->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $case->status)) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Complaints -->
    <div class="card">
        <h3>Pending Complaints</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($recentComplaints as $complaint)
                <div style="padding: 0.75rem; background: #f8fafc; border-radius: 0.5rem; border-left: 3px solid var(--accent-blue);">
                    <div style="font-weight: 600;">{{ $complaint->complainant_name }}</div>
                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                        {{ \Illuminate\Support\Str::limit($complaint->statement, 60) }}
                    </div>
                    <a href="{{ route('admin.complaints.show', $complaint->id) }}" style="font-size: 0.75rem; color: var(--accent-blue); text-decoration: none;">View Detail →</a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
