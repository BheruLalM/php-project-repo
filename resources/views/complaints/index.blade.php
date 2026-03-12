@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Public Complaints</h1>
    <a href="{{ route(auth()->user()->role . '.complaints.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> File New Complaint
    </a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Date Filed</th>
                <th>Complainant Name</th>
                <th>Contact Info</th>
                <th>Status</th>
                <th>Assigned Officer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
            <tr>
                <td>{{ $complaint->created_at->format('d M, Y') }}</td>
                <td><strong>{{ $complaint->complainant_name }}</strong></td>
                <td>
                    @php
                        $contact = $complaint->contact;
                        $masked = str_repeat('*', max(0, strlen($contact) - 4)) . substr($contact, -4);
                    @endphp
                    {{ $masked }}
                </td>
                <td>
                    <span class="badge badge-{{ str_replace('_', '-', $complaint->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </td>
                <td>{{ $complaint->officer->name ?? 'Unassigned' }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route(auth()->user()->role . '.complaints.show', $complaint->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-eye"></i></a>
                        @can('update', $complaint)
                            <a href="{{ route(auth()->user()->role . '.complaints.edit', $complaint->id) }}" class="btn" style="background: #f1f5f9; padding: 0.2rem 0.6rem;"><i class="fa-solid fa-pen-to-square"></i></a>
                        @endcan
                        @can('delete', $complaint)
                            <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Permanently delete this complaint?')">
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
        {{ $complaints->links() }}
    </div>
</div>
@endsection
