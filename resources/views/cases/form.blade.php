@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>{{ isset($case) ? 'Update' : 'File New' }} Case Record</h1>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ isset($case) ? route(auth()->user()->role . '.cases.update', $case->id) : route(auth()->user()->role . '.cases.store') }}" method="POST">
        @csrf
        @if(isset($case)) @method('PUT') @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Crime Type</label>
                <input type="text" name="crime_type" class="form-control" value="{{ old('crime_type', $case->crime_type ?? '') }}" placeholder="E.g., Grand Theft Auto, Assault" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date of Occurrence</label>
                <input type="date" name="date_of_occurrence" class="form-control" value="{{ old('date_of_occurrence', optional($case->date_of_occurrence ?? null)->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $case->location ?? '') }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Incident Description</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $case->description ?? '') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Link Subject (Criminal)</label>
                <select name="criminal_id" class="form-control">
                    <option value="">-- Unknown / Unassigned --</option>
                    @foreach($criminals as $criminal)
                        <option value="{{ $criminal->id }}" {{ old('criminal_id', $case->criminal_id ?? '') == $criminal->id ? 'selected' : '' }}>
                            {{ $criminal->full_name }} ({{ $criminal->status }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Assign Investigating Officer</label>
                <select name="assigned_officer_id" class="form-control">
                    <option value="">-- Unassigned --</option>
                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}" {{ old('assigned_officer_id', $case->assigned_officer_id ?? '') == $officer->id ? 'selected' : '' }}>
                            {{ $officer->name }} (Badge: {{ $officer->badge_number }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">Case Status</label>
            <select name="status" class="form-control" required>
                @foreach(['open', 'under_investigation', 'closed'] as $status)
                    <option value="{{ $status }}" {{ old('status', $case->status ?? '') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">{{ isset($case) ? 'Save Updates' : 'File Case Report' }}</button>
            <a href="{{ route(auth()->user()->role . '.cases.index') }}" class="btn" style="background: #e2e8f0;">Cancel</a>
        </div>
    </form>
</div>
@endsection
