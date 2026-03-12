@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>{{ isset($complaint) ? 'Edit' : 'File New' }} Complaint</h1>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ isset($complaint) ? route(auth()->user()->role . '.complaints.update', $complaint->id) : route(auth()->user()->role . '.complaints.store') }}" method="POST">
        @csrf
        @if(isset($complaint)) @method('PUT') @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Complainant Name</label>
                <input type="text" name="complainant_name" class="form-control" value="{{ old('complainant_name', $complaint->complainant_name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact Phone / Email</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact', $complaint->contact ?? '') }}" required placeholder="Will be encrypted in database">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Official Statement</label>
            <textarea name="statement" class="form-control" rows="6" required>{{ old('statement', $complaint->statement ?? '') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Link to Existing Case (Optional)</label>
                <select name="crime_record_id" class="form-control">
                    <option value="">-- No Linked Case --</option>
                    @foreach($cases as $case)
                        <option value="{{ $case->id }}" {{ old('crime_record_id', $complaint->crime_record_id ?? '') == $case->id ? 'selected' : '' }}>
                            {{ $case->case_number }} ({{ $case->crime_type }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Assign Investigating Officer</label>
                <select name="assigned_officer_id" class="form-control">
                    <option value="">-- Unassigned --</option>
                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}" {{ old('assigned_officer_id', $complaint->assigned_officer_id ?? '') == $officer->id ? 'selected' : '' }}>
                            {{ $officer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">Complaint Status</label>
            <select name="status" class="form-control" required>
                @foreach(['open', 'under_investigation', 'closed'] as $status)
                    <option value="{{ $status }}" {{ old('status', $complaint->status ?? '') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">{{ isset($complaint) ? 'Save Updates' : 'File Complaint' }}</button>
            <a href="{{ route(auth()->user()->role . '.complaints.index') }}" class="btn" style="background: #e2e8f0;">Cancel</a>
        </div>
    </form>
</div>
@endsection
