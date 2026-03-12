@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route(auth()->user()->role . '.cases.index') }}" style="text-decoration: none; color: var(--accent-blue);">
        <i class="fa-solid fa-arrow-left"></i> Back to Cases
    </a>
    
    @can('update', $case)
        <a href="{{ route(auth()->user()->role . '.cases.edit', $case->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Update Case Status
        </a>
    @endcan
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Detail & Evidence -->
    <div>
        <div class="card">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
                <div>
                    <h2 style="margin: 0; color: var(--primary-navy);">{{ $case->case_number }}</h2>
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Filed: {{ $case->created_at->format('M d, Y') }}</div>
                </div>
                <div style="text-align: right;">
                    <span class="badge badge-{{ str_replace('_', '-', $case->status) }}" style="font-size: 1rem;">
                        {{ ucfirst(str_replace('_', ' ', $case->status)) }}
                    </span>
                    <div style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.5rem;">
                        Assigned To: <strong>{{ $case->officer->name ?? 'None' }}</strong>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <strong style="display: block; font-size: 0.75rem; color: var(--text-muted);">Crime Type</strong>
                    {{ $case->crime_type }}
                </div>
                <div>
                    <strong style="display: block; font-size: 0.75rem; color: var(--text-muted);">Date of Occurrence</strong>
                    {{ $case->date_of_occurrence->format('F d, Y') }}
                </div>
                <div style="grid-column: span 2;">
                    <strong style="display: block; font-size: 0.75rem; color: var(--text-muted);">Location</strong>
                    {{ $case->location }}
                </div>
            </div>

            <div>
                <strong style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Incident Description</strong>
                <p style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; margin: 0; font-family: monospace;">
                    {!! nl2br(e($case->description)) !!}
                </p>
            </div>
        </div>

        <!-- Evidence Section -->
        <div class="card">
            <h3><i class="fa-solid fa-box-archive"></i> Evidence Repository</h3>
            
            @can('update', $case)
                <form action="{{ route(auth()->user()->role === 'admin' ? 'admin.evidence.store' : 'officer.evidence.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed #cbd5e1;">
                    @csrf
                    <input type="hidden" name="crime_record_id" value="{{ $case->id }}">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div style="flex: 1;">
                            <label class="form-label">Upload File (Image/PDF, max 5MB)</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div style="flex: 1;">
                            <label class="form-label">Type</label>
                            <select name="file_type" class="form-control" required>
                                <option value="image">Image / Photograph</option>
                                <option value="pdf">Document / PDF</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div style="flex: 2;">
                            <label class="form-label">Description / Log Entry</label>
                            <input type="text" name="description" class="form-control" placeholder="E.g., Weapon found at site" required>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Secure Upload</button>
                        </div>
                    </div>
                </form>
            @endcan

            <table class="table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Uploaded By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($case->evidences as $evo)
                        <tr>
                            <td>
                                <a href="{{ $evo->file_url }}" target="_blank" style="color: var(--accent-blue);">
                                    <i class="fa-solid {{ $evo->file_type === 'pdf' ? 'fa-file-pdf' : 'fa-image' }}"></i> View
                                </a>
                            </td>
                            <td>{{ ucfirst($evo->file_type) }}</td>
                            <td>{{ $evo->description }}</td>
                            <td>{{ $evo->uploader->name ?? 'System' }}<br><span style="font-size: 0.7rem; color: #94a3b8;">{{ $evo->created_at->format('M d Y') }}</span></td>
                            <td>
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.evidence.destroy', $evo->id) }}" method="POST" onsubmit="return confirm('Delete this secure evidence?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1rem;">No evidence logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Sidebar (Timeline & Links) -->
    <div>
        <!-- Case Timeline UI -->
        <div class="card">
            <h3>Investigation Timeline</h3>
            <ul class="timeline">
                <li class="timeline-item {{ $case->created_at ? 'active' : '' }}">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <strong>Incident Occurred</strong><br>
                        <small>{{ $case->date_of_occurrence->format('M d, Y') }}</small>
                    </div>
                </li>
                <li class="timeline-item {{ $case->status !== 'open' ? 'active' : '' }}">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <strong>Under Investigation</strong><br>
                        <small>{{ $case->updated_at > $case->created_at ? $case->updated_at->format('M d, Y') : 'Pending Update' }}</small>
                    </div>
                </li>
                <li class="timeline-item {{ in_array($case->status, ['closed', 'archived']) ? 'active' : '' }}" style="margin-bottom: 0;">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <strong>Case Closed</strong><br>
                        <small>{{ $case->status === 'closed' || $case->status === 'archived' ? $case->updated_at->format('M d, Y') : 'Pending Resolution' }}</small>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Subject Details -->
        <div class="card">
            <h3>Subject (Criminal) Link</h3>
            @if($case->criminal)
                <div style="text-align: center; padding: 1rem 0;">
                    <img src="{{ $case->criminal->photo_url }}" alt="Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 0.5rem;">
                    <div style="font-weight: bold; font-size: 1.1rem;">{{ $case->criminal->full_name }}</div>
                    <span class="badge badge-{{ $case->criminal->status }}" style="margin-bottom: 1rem; display: inline-block;">{{ ucfirst($case->criminal->status) }}</span>
                    
                    <a href="{{ route(auth()->user()->role . '.criminals.show', $case->criminal->id) }}" class="btn btn-primary" style="display: block; width: 100%; box-sizing: border-box; text-align: center;">View Full Profile</a>
                </div>
            @else
                <div style="color: var(--text-muted); text-align: center; padding: 2rem;">
                    No known suspect assigned.
                </div>
            @endif
        </div>
        
        <!-- Linked Complaints -->
        @if($case->complaints->count() > 0)
        <div class="card">
            <h3>Linked Complaints</h3>
            <ul style="padding-left: 1.2rem; margin: 0;">
                @foreach($case->complaints as $complaint)
                    <li style="margin-bottom: 0.5rem;">
                        <a href="{{ route(auth()->user()->role . '.complaints.show', $complaint->id) }}">{{ $complaint->complainant_name }}</a>
                        <span class="badge badge-{{ $complaint->status }}" style="font-size: 0.65rem;">{{ ucfirst($complaint->status) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection
