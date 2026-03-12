@extends('layouts.app')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1>Officer Dashboard</h1>
    <p class="text-muted">My Assigned Cases & Responsibilities</p>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div style="font-size: 0.875rem; opacity: 0.8;">My Open Cases</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['open'] }}</div>
    </div>
    <div class="stat-card yellow">
        <div style="font-size: 0.875rem; opacity: 0.8;">My Investigations</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['under_investigation'] }}</div>
    </div>
    <div class="stat-card green">
        <div style="font-size: 0.875rem; opacity: 0.8;">My Closed Jobs</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['closed'] }}</div>
    </div>
    <div class="stat-card" style="background: var(--sidebar-bg);">
        <div style="font-size: 0.875rem; opacity: 0.8;">Assigned Complaints</div>
        <div style="font-size: 2rem; font-weight: bold;">{{ $stats['total_complaints'] }}</div>
    </div>
</div>

<div class="card">
    <h3>My Active Investigations</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Case #</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($myCases->whereIn('status', ['open', 'under_investigation']) as $case)
                <tr>
                    <td><strong>{{ $case->case_number }}</strong></td>
                    <td>{{ $case->crime_type }}</td>
                    <td>{{ $case->criminal->full_name ?? 'N/A' }}</td>
                    <td>{{ $case->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('officer.cases.show', $case->id) }}" class="btn btn-primary" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">Manage</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No active assignments.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Pending My Follow-up (Complaints)</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Complainant</th>
                <th>Status</th>
                <th>Filing Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($myComplaints->where('status', 'open') as $complaint)
                <tr>
                    <td>{{ $complaint->complainant_name }}</td>
                    <td><span class="badge badge-open">Open</span></td>
                    <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('officer.complaints.show', $complaint->id) }}" class="btn btn-primary" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">Review</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
