@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>System Intelligence & Reports</h1>
        <p class="text-muted">Aggregate statistics for {{ now()->format('F Y') }}</p>
    </div>
    
    <form action="{{ route('admin.reports.archive') }}" method="POST" onsubmit="return confirm('This will archive all CLOSED cases older than 10 years. Are you absolutely certain?')">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-box-archive"></i> Run Auto-Archive (10+ Yr Cases)
        </button>
    </form>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Operational Status -->
    <div class="card">
        <h3>Current Operational Workload</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Case Status</th>
                    <th style="text-align: right;">Total Volume</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-open">Open / Untouched</span></td>
                    <td style="text-align: right; font-weight: bold; font-size: 1.2rem;">{{ $report['total_open'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-investigating">Under Investigation</span></td>
                    <td style="text-align: right; font-weight: bold; font-size: 1.2rem;">{{ $report['total_under_investigation'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-closed">Closed (Resolved)</span></td>
                    <td style="text-align: right; font-weight: bold; font-size: 1.2rem;">{{ $report['total_closed'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-archived">Archived (Cold/Old)</span></td>
                    <td style="text-align: right; font-weight: bold; font-size: 1.2rem;">{{ $report['total_archived'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Crime Typology -->
    <div class="card">
        <h3>Crime Typology Distribution (Current Month)</h3>
        @if(empty($report['by_crime_type']))
            <div style="color: var(--text-muted); padding: 1rem 0;">No crime data recorded for this month yet.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Crime Classification</th>
                        <th style="text-align: right;">Incidents Recorded</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['by_crime_type'] as $type => $count)
                    <tr>
                        <td>{{ $type }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<div class="card" style="margin-top: 2rem; background: var(--sidebar-bg); color: white;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin-top: 0;">Public Trust Metric</h3>
            <p style="margin-bottom: 0; opacity: 0.8;">Total civilian complaints filed this month needing review.</p>
        </div>
        <div style="font-size: 3rem; font-weight: 800; color: var(--accent-blue);">
            {{ $report['new_complaints'] }}
        </div>
    </div>
</div>
@endsection
