@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route(auth()->user()->role . '.complaints.index') }}" style="text-decoration: none; color: var(--accent-blue);">
        <i class="fa-solid fa-arrow-left"></i> Back to Complaints
    </a>
    
    @can('update', $complaint)
        <a href="{{ route(auth()->user()->role . '.complaints.edit', $complaint->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Edit Complaint
        </a>
    @endcan
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Complaint Content -->
    <div class="card">
        <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h2 style="margin: 0; color: var(--primary-navy);">Complaint Detail</h2>
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Filed on {{ $complaint->created_at->format('M d, Y') }}</div>
                </div>
                <div>
                    <span class="badge badge-{{ str_replace('_', '-', $complaint->status) }}" style="font-size: 1rem;">
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <strong style="display: block; font-size: 0.75rem; color: var(--text-muted);">Complainant Name</strong>
                <div style="font-size: 1.1rem;">{{ $complaint->complainant_name }}</div>
            </div>
            <div>
                <strong style="display: block; font-size: 0.75rem; color: var(--text-muted);">Secure Contact Info</strong>
                <div style="font-size: 1.1rem;">
                    {{ $complaint->contact }} <!-- Accessed without mask here as it's the detail view -->
                </div>
            </div>
        </div>

        <div>
            <strong style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Complainant Statement</strong>
            <p style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; line-height: 1.6; border-left: 4px solid var(--accent-blue);">
                {!! nl2br(e($complaint->statement)) !!}
            </p>
        </div>
    </div>

    <!-- Right Sidebar Links -->
    <div>
        <div class="card">
            <h3>Assigned Officer</h3>
            @if($complaint->officer)
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <i class="fa-solid fa-user-shield" style="font-size: 2rem; color: var(--text-muted);"></i>
                    <div>
                        <div style="font-weight: bold;">{{ $complaint->officer->name }}</div>
                        <div style="font-size: 0.875rem; color: var(--text-muted);">Badge: {{ $complaint->officer->badge_number }}</div>
                    </div>
                </div>
            @else
                <div style="color: var(--text-muted); text-align: center; padding: 1rem;">
                    No officer assigned yet.
                </div>
            @endif
        </div>

        <div class="card">
            <h3>Linked Case Record</h3>
            @if($complaint->caseRecord)
                <div style="text-align: center; padding: 1rem 0;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-navy); margin-bottom: 0.5rem;">
                        {{ $complaint->caseRecord->case_number }}
                    </div>
                    <div style="margin-bottom: 1rem; color: var(--text-muted);">
                        Type: {{ $complaint->caseRecord->crime_type }}
                    </div>
                    <a href="{{ route(auth()->user()->role . '.cases.show', $complaint->caseRecord->id) }}" class="btn btn-primary" style="display: block; width: 100%; box-sizing: border-box;">
                        View Full Case Detail
                    </a>
                </div>
            @else
                <div style="color: var(--text-muted); text-align: center; padding: 1rem;">
                    This complaint has not been escalated to a formal case record.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
