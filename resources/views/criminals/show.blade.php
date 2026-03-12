@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route(auth()->user()->role . '.criminals.index') }}" style="text-decoration: none; color: var(--accent-blue);">
        <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
</div>

<div style="display: grid; grid-template-columns: 350px 1fr; gap: 2rem;">
    <!-- Profile Sidebar -->
    <div class="card" style="text-align: center;">
        <img src="{{ $criminal->photo_url }}" alt="Mugshot" style="width: 200px; height: 200px; border-radius: 1rem; object-fit: cover; margin-bottom: 1.5rem; border: 5px solid #f8fafc;">
        <h2 style="margin: 0;">{{ $criminal->full_name }}</h2>
        <div style="color: var(--text-muted); margin-bottom: 1rem;">"{{ $criminal->alias ?? 'No Alias' }}"</div>
        <span class="badge badge-{{ $criminal->status }}" style="font-size: 1rem;">
            {{ ucfirst($criminal->status) }}
        </span>
        
        <div style="text-align: left; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">
            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.75rem; color: var(--text-muted); display: block;">DOB</label>
                <strong>{{ $criminal->date_of_birth ? $criminal->date_of_birth->format('d M, Y') : 'Unknown' }}</strong>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.75rem; color: var(--text-muted); display: block;">Nationality</label>
                <strong>{{ $criminal->nationality ?? 'N/A' }}</strong>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.75rem; color: var(--text-muted); display: block;">Physical Markers</label>
                <p style="margin: 0; font-size: 0.875rem;">{{ $criminal->physical_markers ?? 'None recorded.' }}</p>
            </div>
        </div>
    </div>

    <!-- Case History -->
    <div class="card">
        <h3>Criminal History / Linked Cases</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Case #</th>
                    <th>Crime Type</th>
                    <th>Date</th>
                    <th>Officer</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($criminal->crimeRecords as $case)
                <tr>
                    <td><strong>{{ $case->case_number }}</strong></td>
                    <td>{{ $case->crime_type }}</td>
                    <td>{{ $case->date_of_occurrence->format('M Y') }}</td>
                    <td>{{ $case->officer->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ str_replace('_', '-', $case->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $case->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No crime records found for this individual.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
