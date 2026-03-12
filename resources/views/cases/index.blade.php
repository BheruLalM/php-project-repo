@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Case Records</h1>
    <a href="{{ route(auth()->user()->role . '.cases.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> File New Case
    </a>
</div>

<div class="card">
    <form action="{{ route(auth()->user()->role . '.cases.index') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input type="text" name="search" class="form-control" placeholder="Search case number or location..." value="{{ request('search') }}" style="flex: 2;">
        
        <select name="status" class="form-control" style="flex: 1;">
            <option value="">All Statuses</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="under_investigation" {{ request('status') == 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route(auth()->user()->role . '.cases.index') }}" class="btn btn-secondary" style="background: #e2e8f0;">Reset</a>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Case #</th>
                <th>Subject</th>
                <th>Type</th>
                <th>Location</th>
                <th>Occurred</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cases as $case)
            <tr>
                <td><strong>{{ $case->case_number }}</strong></td>
                <td>
                    @if($case->criminal)
                        <a href="{{ route(auth()->user()->role . '.criminals.show', $case->criminal_id) }}">{{ $case->criminal->full_name }}</a>
                    @else
                        <span class="text-muted">Unknown</span>
                    @endif
                </td>
                <td>{{ $case->crime_type }}</td>
                <td>{{ \Illuminate\Support\Str::limit($case->location, 30) }}</td>
                <td>{{ $case->date_of_occurrence->format('d M, Y') }}</td>
                <td>
                    <span class="badge badge-{{ str_replace('_', '-', $case->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $case->status)) }}
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route(auth()->user()->role . '.cases.show', $case->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-eye"></i></a>
                        @can('update', $case)
                            <a href="{{ route(auth()->user()->role . '.cases.edit', $case->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-pen-to-square"></i></a>
                        @endcan
                        @can('delete', $case)
                            <form action="{{ route('admin.cases.destroy', $case->id) }}" method="POST" onsubmit="return confirm('Archive/Delete this case?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.2rem 0.6rem;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $cases->withQueryString()->links() }}
    </div>
</div>
@endsection
